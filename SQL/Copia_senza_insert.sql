-- ***** CREAZIONE USER *****

CREATE USER 'Studente'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Personale-Docente'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Personale-Ata'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Personale-Segreteria'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Titolare'@'localhost' IDENTIFIED BY 'Operatore';
CREATE USER 'Addetto-consegne'@'localhost' IDENTIFIED BY 'Operatore';
CREATE USER 'Addetto-vendite'@'localhost' IDENTIFIED BY 'Operatore';
CREATE USER 'Fornitore'@'localhost' IDENTIFIED BY 'Fornitore';

-- Creazione Database
CREATE DATABASE owlbreak;

USE owlbreak;

/* CREAZIONE TABELLE */

CREATE TABLE cliente (
    email VARCHAR(100) PRIMARY KEY,
    passw VARCHAR(255) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    luogoConsegna VARCHAR(100) NOT NULL,
    tipoCliente VARCHAR(30) NOT NULL CHECK (
        tipoCliente IN (
            'Studente',
            'Personale-Docente',
            'Personale-Ata',
            'Personale-Segreteria'
        )
    )
);

CREATE TABLE prodotto (
    nome VARCHAR(50) PRIMARY KEY,
    prezzo DECIMAL(6,2) NOT NULL,
    disponibilità BOOLEAN NOT NULL
);

CREATE TABLE operatore (
    CodiceID INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    passw VARCHAR(255) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    ruolo VARCHAR(30) NOT NULL CHECK (
        ruolo IN (
            'Titolare',
            'Addetto-consegne',
            'Addetto-vendite'
        )
    )
);

CREATE TABLE ordine (
    data DATE,
    ora TIME,
    emailCliente VARCHAR(100),
    nomeProdotto VARCHAR(50),
    consegnato BOOLEAN DEFAULT FALSE,
    quantità INT NOT NULL CHECK (quantità > 0),
    OperatoreID INT,
    PRIMARY KEY (data, ora, emailCliente, nomeProdotto),
    FOREIGN KEY (emailCliente) REFERENCES Cliente(email),
    FOREIGN KEY (nomeProdotto) REFERENCES Prodotto(nome),
    FOREIGN KEY (OperatoreID) REFERENCES Operatore(CodiceID)
);

CREATE TABLE ingrediente (
    nome VARCHAR(50) PRIMARY KEY,
    allergeni VARCHAR(200),
    quantità INT NOT NULL CHECK (quantità >= 0)
);

CREATE TABLE composizione (
    nomeProdotto VARCHAR(50),
    nomeIngrediente VARCHAR(50),
    PRIMARY KEY (nomeProdotto, nomeIngrediente),
    FOREIGN KEY (nomeProdotto) REFERENCES Prodotto(nome),
    FOREIGN KEY (nomeIngrediente) REFERENCES Ingrediente(nome)
);

CREATE TABLE fornitore (
    CodiceID INT PRIMARY KEY AUTO_INCREMENT,
    nomeTitolare VARCHAR(50),
    nomeAzienda VARCHAR(50),
    email VARCHAR(100),
    passw VARCHAR(255)
);

CREATE TABLE rifornimento (
    CodiceID INT NOT NULL AUTO_INCREMENT,
    ingrediente VARCHAR(50) NOT NULL,
    quantità INT NOT NULL,
    data DATE NOT NULL,
    ora TIME NOT NULL,
    consegnato BOOLEAN DEFAULT FALSE,
    OperatoreID INT,
    FornitoreID INT,
    PRIMARY KEY (CodiceID, ingrediente),
    FOREIGN KEY (OperatoreID) REFERENCES Operatore(CodiceID),
    FOREIGN KEY (FornitoreID) REFERENCES Fornitore(CodiceID)
);

/*tabella aggiunta per assegnare lo stesso operatore 
a tutti gli ordini con uguale luogo di consegna*/
CREATE TABLE consegna (
    luogoConsegna VARCHAR(100) PRIMARY KEY,
    OperatoreID INT,
    FOREIGN KEY (OperatoreID) REFERENCES Operatore(CodiceID)
);

# Impedisce la modifica ai clienti tipo="Studente" sul luogo di consegna 
-- non va bene pk gli studenti non lo possono modificare da soli ma i segretari possono cambiarglielo
/*DELIMITER $$
CREATE TRIGGER check_luogoConsegna_modifica
BEFORE UPDATE ON cliente
FOR EACH ROW
BEGIN
    IF OLD.tipoCliente = 'Studente' AND NEW.luogoConsegna <> OLD.luogoConsegna THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gli studenti non possono modificare il luogo di consegna.';
    END IF;
END$$
DELIMITER ;*/


-- Trigger per permettere ordini solo dalle 8 alle 10
DELIMITER $$
CREATE TRIGGER verifica_orario_ordine
BEFORE INSERT ON ordine -- non faccio anche il trigger before update perché questi campi non li aggiorna nessuno
FOR EACH ROW
BEGIN
  DECLARE giorno_settimana INT;
  SET giorno_settimana = WEEKDAY(NEW.data); -- Lunedì=0, ..., Domenica=6

  -- Controllo: no domenica
  IF giorno_settimana = 6 THEN
    SIGNAL SQLSTATE '45000' 
    SET MESSAGE_TEXT = 'Non si possono fare ordini la domenica';
  END IF;

  -- Controllo: fascia oraria 08:00–10:00
  IF NEW.ora < '08:00:00' OR NEW.ora > '10:00:00' THEN
    SIGNAL SQLSTATE '45000' 
    SET MESSAGE_TEXT = 'Gli ordini sono consentiti solo tra le 08:00 e le 10:00';
  END IF;
END$$
DELIMITER ;


/*trigger per aggiornare la disponibilità dei prodotti 
e la quantità rimasta degli ingredienti dopo un ordine*/
DELIMITER $$
CREATE TRIGGER aggiorna_ingredienti_ordine
AFTER INSERT ON ordine
FOR EACH ROW
BEGIN
    -- aggiorno le quantità degli ingredienti presenti nei prodotti ordinati
    UPDATE ingrediente
    SET quantità = quantità - NEW.quantità
    WHERE nome IN (
        SELECT nomeIngrediente
        FROM composizione
        WHERE nomeProdotto = NEW.nomeProdotto
    );
    
    -- L'upate di ingrediente attiva il trigger per aggiornare la disponibilità dei prodotti

END; $$
DELIMITER ;

/*trigger per aggiornare la disponibilità dei prodotti 
e la quantità degli ingredienti dopo un rifornimento*/
DELIMITER $$
CREATE TRIGGER aggiorna_ingredienti_rifornimento
AFTER UPDATE ON Rifornimento
FOR EACH ROW
BEGIN
    
    IF OLD.consegnato = 0 AND NEW.consegnato = 1 THEN

        -- aggiornamento quantità ingredienti
        UPDATE Ingrediente
        SET quantità = quantità + NEW.quantità
        WHERE nome = NEW.ingrediente;

        -- L'upate di ingrediente attiva il trigger per aggiornare la disponibilità dei prodotti

    END IF;

END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER aggiorna_disp_prodotti
AFTER UPDATE ON ingrediente
FOR EACH ROW
BEGIN

    -- se qualche ingrediente è a 0 metto a FALSE la disponibilità di un prodotto
    UPDATE prodotto
    SET disponibilità = FALSE
    WHERE nome IN (
        SELECT DISTINCT nomeProdotto
        FROM composizione
        WHERE nomeIngrediente IN (
           SELECT nome
           FROM ingrediente
           WHERE quantità=0
        )
    );

     -- ripristino disponibilità prodotti
    UPDATE Prodotto
    SET disponibilità = TRUE
    WHERE disponibilità = FALSE AND nome NOT IN (
        SELECT DISTINCT nomeProdotto
        FROM Composizione
        WHERE nomeIngrediente IN (
            SELECT nome
            FROM Ingrediente
            WHERE quantità = 0
        )
    );

END; $$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE effettua_ordine(
    IN p_email_cliente VARCHAR(100),
    IN p_nome_prodotto VARCHAR(50),
    IN p_quantita INT,
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_prodotto_disponibile BOOLEAN;
    DECLARE v_cliente_esiste BOOLEAN;
    DECLARE v_data_corrente DATE;
    DECLARE v_ora_corrente TIME;
    DECLARE v_giorno_settimana INT;
    DECLARE v_operatore_id INT;
    DECLARE v_ingredienti_disponibili BOOLEAN DEFAULT TRUE;
    DECLARE v_ingrediente_non_disponibile VARCHAR(50);
    DECLARE v_luogo_consegna VARCHAR(100);
    DECLARE v_min_assegnamenti INT;

    this_procedure: BEGIN

        -- Inizializza variabili
        SET v_data_corrente = CURDATE();
        SET v_ora_corrente = CURTIME();
        SET v_giorno_settimana = WEEKDAY(v_data_corrente); -- Lunedì=0, ..., Domenica=6
        
        -- Verifica se è domenica
        IF v_giorno_settimana = 6 THEN
            SET p_messaggio = 'Non è possibile effettuare ordini di domenica';
            LEAVE this_procedure;
        END IF;
        
        -- Verifica orario (solo dalle 8 alle 10)
        IF (v_ora_corrente < '08:00:00' OR v_ora_corrente > '10:00:00') THEN
            SET p_messaggio = 'Gli ordini sono accettati solo dalle 8:00 alle 10:00';
            LEAVE this_procedure;
        END IF;
        
        -- Verifica esistenza cliente
        SELECT COUNT(*) > 0 INTO v_cliente_esiste
        FROM cliente
        WHERE email = p_email_cliente;
        
        IF NOT v_cliente_esiste THEN
            SET p_messaggio = 'Cliente non trovato';
            LEAVE this_procedure;
        END IF;
        
        -- Verifica disponibilità prodotto
        SELECT disponibilità INTO v_prodotto_disponibile
        FROM prodotto
        WHERE nome = p_nome_prodotto;
        
        IF NOT v_prodotto_disponibile THEN -- verifica disponibilità prodotto
            SET p_messaggio = 'Prodotto non disponibile';
            LEAVE this_procedure;
        ELSEIF p_quantita <= 0 THEN -- verifica quantità positiva
            SET p_messaggio = 'La quantità deve essere maggiore di zero';
            LEAVE this_procedure;
        END IF;

        -- ***** CONTROLLO DISPONIBILITÀ QUANTITÀ INGREDIENTI PER N PRODOTTI *****

        -- Verifica disponibilità ingredienti
        -- Controlliamo se tutti gli ingredienti sono disponibili nella quantità necessaria
        -- Troviamo l'ingrediente con la minore disponibilità proporzionale
        /* SELECT i.nome INTO v_ingrediente_non_disponibile
        FROM ingrediente i
        JOIN composizione c ON i.nome = c.nomeIngrediente
        WHERE c.nomeProdotto = p_nome_prodotto
        AND i.quantità < p_quantita  -- Se la quantità disponibile è minore di quella richiesta
        LIMIT 1;  -- Possiamo usare una variabile temporanea per evitare LIMIT  */

        -- Alternativa senza LIMIT:
        SELECT MIN(i.nome) INTO v_ingrediente_non_disponibile
        FROM ingrediente i
        JOIN composizione c ON i.nome = c.nomeIngrediente
        WHERE c.nomeProdotto = p_nome_prodotto AND i.quantità < p_quantita;


        IF v_ingrediente_non_disponibile IS NOT NULL THEN
            SET v_ingredienti_disponibili = FALSE;
        END IF;

        IF NOT v_ingredienti_disponibili THEN
            SET p_messaggio = CONCAT('Quantità insufficiente dell\'ingrediente: ', v_ingrediente_non_disponibile);
            LEAVE this_procedure;
        END IF;
        

        -- ***** SELEZIONE OPERATORE DISPONIBILE *****

        -- un operatore deve prendere in carico tutte tuple di ordine con stesso luogo consegna
        -- gli unici operatori che possono prendere in carico gli ordini sono gli addetti alle consegne

        -- Recupera il luogo di consegna del cliente
        SELECT luogoConsegna INTO v_luogo_consegna
        FROM cliente
        WHERE email = p_email_cliente;

        -- Verifica se il luogo è già associato a un operatore
        SELECT OperatoreID INTO v_operatore_id
        FROM consegna
        WHERE luogoConsegna = v_luogo_consegna;

        -- Se non è già assegnato, scegli l'operatore più bilanciato
        IF v_operatore_id IS NULL THEN

            -- Trova il numero minimo di assegnamenti per operatore
            SELECT MIN(assegnamenti) INTO v_min_assegnamenti
            FROM (
                SELECT o.CodiceID, COUNT(c.luogoConsegna) AS assegnamenti
                FROM operatore o
                LEFT JOIN consegna c ON o.CodiceID = c.OperatoreID
                WHERE o.ruolo = 'Addetto-consegne'
                GROUP BY o.CodiceID
            ) AS conteggi;

            -- Seleziona l'operatore con quel numero minimo di assegnamenti e CodiceID più basso
            /*SELECT o.CodiceID INTO v_operatore_id
            FROM operatore o
            LEFT JOIN consegna c ON o.CodiceID = c.OperatoreID
            WHERE o.ruolo = 'Addetto-consegne'
            GROUP BY o.CodiceID
            HAVING COUNT(c.luogoConsegna) = v_min_assegnamenti
            AND o.CodiceID = MIN(o.CodiceID);*/ -- se due op hanno = num consegne restituisce più di una tupla

            -- Prendi il CodiceID più piccolo tra quelli che hanno conteggio = v_min_assegnamenti
            SELECT MIN(o2.CodiceID)
              INTO v_operatore_id
            FROM operatore o2
            WHERE o2.ruolo = 'Addetto-consegne'
              AND (
                SELECT COUNT(*) 
                  FROM consegna c2 
                  WHERE c2.OperatoreID = o2.CodiceID
              ) = v_min_assegnamenti;


            -- Se nessun operatore disponibile
            IF v_operatore_id IS NULL THEN
                SET p_messaggio = 'Nessun operatore disponibile';
                LEAVE this_procedure;
            END IF;

            -- Assegna l'operatore a questo nuovo luogo
            INSERT INTO consegna (luogoConsegna, OperatoreID)
            VALUES (v_luogo_consegna, v_operatore_id);
        END IF;
        
        -- Inserisci ordine
        INSERT INTO ordine (data, ora, emailCliente, nomeProdotto, consegnato, quantità, OperatoreID)
        VALUES (v_data_corrente, v_ora_corrente, p_email_cliente, p_nome_prodotto, FALSE, p_quantita, v_operatore_id);
        
        SET p_messaggio = 'Ordine effettuato con successo';
 
        /* NON FUNZIONA ****
        -- Recupera il luogo di consegna del cliente
        SELECT luogoConsegna INTO v_luogo_consegna 
        FROM cliente 
        WHERE email = p_email_cliente;
        
        -- Verifica se il luogo di consegna è già mappato a un operatore
        SELECT OperatoreID INTO v_operatore_id 
        FROM consegna 
        WHERE luogoConsegna = v_luogo_consegna;
        
        -- Se il luogo non è mappato, assegna un operatore di tipo "Addetto-consegne"
        IF v_operatore_id IS NULL THEN
            -- Seleziona un operatore 
            SELECT CodiceID INTO v_operatore_id
            FROM (
                SELECT CodiceID, RAND() AS r
                FROM operatore
                WHERE ruolo = 'Addetto-consegne'
            ) AS rand_addettiC
            GROUP BY CodiceID
            HAVING r = MIN(r);
            
            -- Controlla se è stato trovato un operatore disponibile
            IF v_operatore_id IS NULL THEN
                SET p_messaggio = 'Nessun operatore di consegna disponibile';
                LEAVE this_procedure;
            END IF;
            
            -- Inserisci il nuovo mapping nella tabella consegne
            INSERT INTO consegna (luogoConsegna, OperatoreID)
            VALUES (v_luogo_consegna, v_operatore_id);
        END IF;
        */

    END;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE insert_cliente(
    IN p_nome VARCHAR(50),
    IN p_cognome VARCHAR(50),
    IN p_tipo_cliente VARCHAR(30),
    IN p_luogo_consegna VARCHAR(100),
    IN p_default_pssw VARCHAR(255),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_dominio VARCHAR(30);
    DECLARE v_iniziale VARCHAR(1);
    DECLARE v_nominativo_base VARCHAR(50);
    DECLARE v_nominativo VARCHAR(60);
    DECLARE v_email VARCHAR(100);
    DECLARE v_numero INT DEFAULT 0;
    DECLARE v_tipo_cliente VARCHAR(30);
    DECLARE v_nome VARCHAR(50);
    DECLARE v_cognome VARCHAR(50);

    this_procedure: BEGIN

        CASE LOWER(p_tipo_cliente)
            WHEN 'studente' THEN SET v_tipo_cliente = 'Studente';
            WHEN 'personale-docente' THEN SET v_tipo_cliente = 'Personale-Docente';
            WHEN 'personale-ata' THEN SET v_tipo_cliente = 'Personale-Ata';
            WHEN 'personale-segreteria' THEN SET v_tipo_cliente = 'Personale-Segreteria';
            WHEN 'personale docente' THEN SET v_tipo_cliente = 'Personale-Docente';
            WHEN 'personale ata' THEN SET v_tipo_cliente = 'Personale-Ata';
            WHEN 'personale segreteria' THEN SET v_tipo_cliente = 'Personale-Segreteria';
        ELSE
            SET p_messaggio = CONCAT('Tipo cliente non valido: ', p_tipo_cliente);
            LEAVE this_procedure;
        END CASE;

        -- creazione mail univoca
        IF v_tipo_cliente = 'Studente' THEN
            SET v_dominio = 'studenti.boscogrigio.it';
        ELSE
            SET v_dominio = 'boscogrigio.it';
        END IF;

        SET v_iniziale = LEFT(p_nome, 1);
        SET v_nominativo_base = CONCAT_WS('.', v_iniziale, p_cognome);

        -- Controlla quanti clienti hanno già quella base email
        SELECT COUNT(*) INTO v_numero
        FROM cliente
        WHERE email LIKE CONCAT(LOWER(v_nominativo_base), '%@', v_dominio);

        -- Aggiungi numero se necessario
        IF v_numero = 0 THEN
            SET v_nominativo = v_nominativo_base;
        ELSE
            SET v_nominativo = CONCAT(v_nominativo_base, v_numero);
        END IF;

        -- Mail finale in minuscolo
        SET v_email = LOWER(CONCAT_WS('@', v_nominativo, v_dominio));

        -- Normalizzazione nome e cognome
        SET v_nome = CONCAT( UPPER(LEFT(p_nome, 1)), LOWER(SUBSTRING(p_nome, 2)));
        SET v_cognome = CONCAT( UPPER(LEFT(p_cognome, 1)), LOWER(SUBSTRING(p_cognome, 2)));

        -- Inserimento cliente
        INSERT INTO cliente (nome, cognome, tipoCliente, luogoConsegna, email, passw)
        VALUES (v_nome, v_cognome, v_tipo_cliente, p_luogo_consegna, v_email, p_default_pssw);

        SET p_messaggio = CONCAT('Cliente inserito con email: ', v_email);

    END this_procedure;
END $$
DELIMITER ;


-- procedura per la modifica dei dati di un cliente
DELIMITER $$
CREATE PROCEDURE modifica_cliente(
    IN p_email_corrente VARCHAR (100),
    IN p_nome VARCHAR(50),
    IN p_cognome VARCHAR(50),
    IN p_tipo_cliente VARCHAR(30),
    IN p_luogo_consegna VARCHAR(100),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_dominio VARCHAR(30);
    DECLARE v_iniziale VARCHAR(1);
    DECLARE v_nominativo_base VARCHAR(50);
    DECLARE v_nominativo VARCHAR(60);
    DECLARE v_email VARCHAR(100);
    DECLARE v_numero INT DEFAULT 0;
    DECLARE v_tipo_cliente VARCHAR(30);
    DECLARE v_nome VARCHAR(50);
    DECLARE v_cognome VARCHAR(50);
    DECLARE v_nome_corrente VARCHAR(50);
    DECLARE v_cognome_corrente VARCHAR(50);
    DECLARE v_tipo_corrente VARCHAR(30);
    DECLARE v_cliente_esiste BOOLEAN;
    DECLARE v_n_class VARCHAR(1); /*Queste due variabili mi servono per non cambiare la mail se non è necessario,*/
    DECLARE v_v_class VARCHAR(1); /*tipo se uno era segnato come segretario e poi come docente*/

    this_procedure: BEGIN

        -- Verifica esistenza cliente
        SELECT COUNT(*) > 0 INTO v_cliente_esiste
        FROM cliente
        WHERE email = p_email_corrente;

        IF NOT v_cliente_esiste THEN
            SET p_messaggio = 'Cliente non trovato';
            LEAVE this_procedure;
        END IF;

        -- Recupera i dati attuali del cliente
        SELECT nome, cognome, tipoCliente
        INTO v_nome_corrente, v_cognome_corrente, v_tipo_corrente
        FROM cliente
        WHERE email = p_email_corrente;

        IF (v_tipo_corrente = 'Studente') THEN
            SET v_v_class = 'S';
        ELSE 
            SET v_v_class = 'P';
        END IF;

        -- normalizzazione tipo cliente
        CASE LOWER(p_tipo_cliente)
            WHEN 'studente' THEN 
                BEGIN
                    SET v_tipo_cliente = 'Studente';
                    SET v_n_class = 'S';
                END;
            WHEN 'personale-docente' THEN 
                BEGIN
                    SET v_tipo_cliente = 'Personale-Docente';
                    SET v_n_class = 'P';
                END;
            WHEN 'personale-ata' THEN 
                BEGIN
                    SET v_tipo_cliente = 'Personale-Ata';
                    SET v_n_class = 'P';
                END;
            WHEN 'personale-segreteria' THEN 
                BEGIN
                    SET v_tipo_cliente = 'Personale-Segreteria';
                    SET v_n_class = 'P';
                END;
            WHEN 'personale docente' THEN 
                BEGIN
                    SET v_tipo_cliente = 'Personale-Docente';
                    SET v_n_class = 'P';
                END;
            WHEN 'personale ata' THEN 
                BEGIN
                    SET v_tipo_cliente = 'Personale-Ata';
                    SET v_n_class = 'P';
                END;
            WHEN 'personale segreteria' THEN 
                BEGIN
                    SET v_tipo_cliente = 'Personale-Segreteria';
                    SET v_n_class = 'P';
                END;
        ELSE
            BEGIN
                SET p_messaggio = CONCAT('Tipo cliente non valido: ', p_tipo_cliente);
                LEAVE this_procedure;
            END;
        END CASE;

        IF  /*LOWER(p_nome) <> LOWER(v_nome_corrente)*/
            LEFT(LOWER(p_nome), 1) <> LEFT(LOWER(v_nome_corrente), 1)
            OR LOWER(p_cognome) <> LOWER(v_cognome_corrente) 
            OR (LOWER(v_tipo_cliente) <> LOWER(v_tipo_corrente) AND v_v_class <> v_n_class)THEN

            /*Creouna nuova mail solo se è cambiata l'iniziale del nome o è cambiato il cognome oppure se
            è cambiato il tipo di cliente ma solo se è cambiata la classificazione studente o personale,
            ovvero se c'è bisogno di cambiare dominio*/
        
            -- creazione mail univoca
            IF v_tipo_cliente = 'Studente' THEN
                SET v_dominio = 'studenti.boscogrigio.it';
            ELSE
                SET v_dominio = 'boscogrigio.it';
            END IF;

            SET v_iniziale = LEFT(p_nome, 1);
            SET v_nominativo_base = CONCAT_WS('.', v_iniziale, p_cognome);

            -- Controlla quanti clienti hanno già quella base email
            SELECT COUNT(*) INTO v_numero
            FROM cliente
            WHERE email LIKE CONCAT(LOWER(v_nominativo_base), '%@', v_dominio);

            -- Aggiungi numero se necessario
            IF v_numero = 0 THEN
                SET v_nominativo = v_nominativo_base;
            ELSE
                SET v_nominativo = CONCAT(v_nominativo_base, v_numero);
            END IF;

            -- Mail finale in minuscolo
            SET v_email = LOWER(CONCAT_WS('@', v_nominativo, v_dominio));

        ELSE
            SET v_email = p_email_corrente;
        END IF;

        -- Normalizzazione nome e cognome
        SET v_nome = CONCAT( UPPER(LEFT(p_nome, 1)), LOWER(SUBSTRING(p_nome, 2)));
        SET v_cognome = CONCAT( UPPER(LEFT(p_cognome, 1)), LOWER(SUBSTRING(p_cognome, 2)));

        -- Esegui UPDATE
        UPDATE cliente
        SET nome = v_nome,
            cognome = v_cognome,
            tipoCliente = v_tipo_cliente,
            luogoConsegna = p_luogo_consegna,
            email = v_email
        WHERE email = p_email_corrente;

        SET p_messaggio = CONCAT('Cliente inserito con nuovi dati: ', v_nome,' ', v_cognome,' ', v_email,' ', v_tipo_cliente,' ', p_luogo_consegna);

    END this_procedure;
END $$
DELIMITER ;

-- procedura per eliminare un cliente
DELIMITER $$
CREATE PROCEDURE elimina_cliente(
    IN p_email VARCHAR(100),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_cliente_esiste BOOLEAN;

    this_procedure: BEGIN
        -- Verifica esistenza cliente
        SELECT COUNT(*) > 0 INTO v_cliente_esiste
        FROM cliente
        WHERE email = p_email;

        IF NOT v_cliente_esiste THEN
            SET p_messaggio = 'Cliente non trovato';
            LEAVE this_procedure;
        END IF;

        DELETE FROM cliente
        WHERE email = p_email;

        SET p_messaggio = CONCAT("Cliente con email ", p_email, " eliminato dal database");

    END this_procedure;
END $$
DELIMITER ;


-- procedura per cambiare la pssw
DELIMITER $$
CREATE PROCEDURE cambio_pssw_cliente(
    IN p_email VARCHAR(100),
    IN p_v_pssw VARCHAR (255),
    IN p_n_pssw VARCHAR (255),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_v_pssw VARCHAR(255);
    DECLARE v_cliente_esiste BOOLEAN;

    this_procedure: BEGIN
        -- controllo esistenza cliente
        SELECT COUNT(*) > 0 INTO v_cliente_esiste
        FROM cliente
        WHERE email = p_email;

        IF NOT v_cliente_esiste THEN
            SET p_messaggio = 'Cliente non trovato';
            LEAVE this_procedure;
        END IF;

        -- controllo che la nuova pssw non sia vuota
        IF LENGTH(TRIM(p_n_pssw)) = 0 THEN
            SET p_messaggio = 'La nuova password non può essere vuota';
            LEAVE this_procedure;
        END IF;

        SELECT passw INTO v_v_pssw
        FROM cliente
        WHERE email = p_email;

        -- controllo che la vecchia password inserita dal cliente corrisponda a quella memorizzata del database
        IF p_v_pssw <> v_v_pssw THEN
            SET p_messaggio = 'La password non corrisponde';
            LEAVE this_procedure;
        ELSE 
            UPDATE cliente
            SET passw = p_n_pssw
            WHERE email = p_email;

            SET p_messaggio = 'Password aggiornata con successo!';
        END IF;

    END this_procedure;
END $$
DELIMITER ;


-- procedura per effettuare un rifornimento



/*
-- ****** TUTTE PROCEDURE DI CLAUDE DA CONTROLLARE BENE ******

-- Procedura per permettere agli operaatori di segnare un ordine come consegnato
-- Bisogna farne una simile per i rifornimenti
DELIMITER $$
CREATE PROCEDURE segna_ordine_consegnato(
    IN p_data DATE,
    IN p_ora TIME,
    IN p_email_cliente VARCHAR(100),
    IN p_nome_prodotto VARCHAR(50),
    IN p_operatore_id INT,
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_ordine_esiste BOOLEAN;
    DECLARE v_operatore_corretto BOOLEAN;
    DECLARE v_gia_consegnato BOOLEAN;
    
    segna_consegna: BEGIN
        -- Verifica se l'ordine esiste
        SELECT COUNT(*) > 0 INTO v_ordine_esiste
        FROM ordine
        WHERE data = p_data 
          AND ora = p_ora 
          AND emailCliente = p_email_cliente 
          AND nomeProdotto = p_nome_prodotto;
        
        IF NOT v_ordine_esiste THEN
            SET p_messaggio = 'Ordine non trovato';
            LEAVE segna_consegna;
        END IF;
        
        -- Verifica se l'operatore è quello assegnato all'ordine
        SELECT COUNT(*) > 0 INTO v_operatore_corretto
        FROM ordine
        WHERE data = p_data 
          AND ora = p_ora 
          AND emailCliente = p_email_cliente 
          AND nomeProdotto = p_nome_prodotto
          AND OperatoreID = p_operatore_id;
        
        IF NOT v_operatore_corretto THEN
            SET p_messaggio = 'Non sei autorizzato a modificare questo ordine';
            LEAVE segna_consegna;
        END IF;
        
        -- Verifica se l'ordine è già stato consegnato
        SELECT consegnato INTO v_gia_consegnato
        FROM ordine
        WHERE data = p_data 
          AND ora = p_ora 
          AND emailCliente = p_email_cliente 
          AND nomeProdotto = p_nome_prodotto;
        
        IF v_gia_consegnato THEN
            SET p_messaggio = 'Questo ordine risulta già consegnato';
            LEAVE segna_consegna;
        END IF;
        
        -- Aggiorna l'ordine come consegnato
        UPDATE ordine
        SET consegnato = TRUE
        WHERE data = p_data 
          AND ora = p_ora 
          AND emailCliente = p_email_cliente 
          AND nomeProdotto = p_nome_prodotto;
        
        SET p_messaggio = 'Ordine segnato come consegnato con successo';
    END;
    
END$$
DELIMITER ;

-- Procedura per visualizzare gli ordini assegnati a un operatore
DELIMITER $$
CREATE PROCEDURE VisualizzaOrdiniOperatore(
    IN p_operatoreID INT
)
BEGIN
    SELECT o.data, o.ora, o.emailCliente, c.nome AS nomeCliente, c.cognome AS cognomeCliente,
           c.luogoConsegna, o.nomeProdotto, o.quantità, o.consegnato
    FROM ordine o
    JOIN cliente c ON o.emailCliente = c.email
    WHERE o.OperatoreID = p_operatoreID
    ORDER BY o.data DESC, o.ora DESC;
END $$
DELIMITER ;

DELIMITER $$
-- Procedura per riassegnare un operatore a un luogo di consegna
CREATE PROCEDURE RiassegnaOperatoreConsegna(
    IN p_luogoConsegna VARCHAR(100),
    IN p_nuovoOperatoreID INT
)
BEGIN
    DECLARE v_ruoloOperatore VARCHAR(30);
    
    -- Verifica che il nuovo operatore sia un addetto alle consegne
    SELECT ruolo INTO v_ruoloOperatore 
    FROM operatore 
    WHERE CodiceID = p_nuovoOperatoreID;
    
    IF v_ruoloOperatore != 'Addetto-consegne' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'operatore selezionato non è un addetto alle consegne';
    ELSE
        -- Aggiorna il mapping nella tabella consegne
        UPDATE consegne 
        SET OperatoreID = p_nuovoOperatoreID
        WHERE luogoConsegna = p_luogoConsegna;
        
        -- Aggiorna tutti gli ordini non ancora consegnati per questo luogo
        UPDATE ordine o
        JOIN cliente c ON o.emailCliente = c.email
        SET o.OperatoreID = p_nuovoOperatoreID
        WHERE c.luogoConsegna = p_luogoConsegna
        AND o.consegnato = FALSE;
    END IF;
END $$
DELIMITER ;

*/


-- ***** PRIVILEGI *****

-- PRIVILEGI STUDENTI
GRANT SELECT ON owlbreak.cliente TO 'Studente'@'localhost'; -- per poter recuperare i propri dati
GRANT SELECT ON owlbreak.ordine TO 'Studente'@'localhost';
GRANT SELECT ON owlbreak.prodotto TO 'Studente'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.effettua_ordine TO 'Studente'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_cliente TO 'Studente'@'localhost';


-- PRIVILEGI DOCENTI
GRANT SELECT ON owlbreak.cliente TO 'Personale-Docente'@'localhost'; -- per poter recuperare i propri dati
GRANT SELECT ON owlbreak.ordine TO 'Personale-Docente'@'localhost';
GRANT SELECT ON owlbreak.prodotto TO 'Personale-Docente'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.effettua_ordine TO 'Personale-Docente'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_cliente TO 'Personale-Docente'@'localhost';


-- PRIVILEGI ATA
GRANT SELECT ON owlbreak.cliente TO 'Personale-Ata'@'localhost'; -- per poter recuperare i propri dati
GRANT SELECT ON owlbreak.ordine TO 'Personale-Ata'@'localhost';
GRANT SELECT ON owlbreak.prodotto TO 'Personale-Ata'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.effettua_ordine TO 'Personale-Ata'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_cliente TO 'Personale-Ata'@'localhost';


-- PRIVILEGI SEGRETERIA
GRANT SELECT ON owlbreak.cliente TO 'Personale-Segreteria'@'localhost'; -- per poter recuperare i propri dati e quelli degli altri
GRANT SELECT ON owlbreak.ordine TO 'Personale-Segreteria'@'localhost';
GRANT SELECT ON owlbreak.prodotto TO 'Personale-Segreteria'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.effettua_ordine TO 'Personale-Segreteria'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.insert_cliente TO 'Personale-Segreteria'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.modifica_cliente TO 'Personale-Segreteria'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.elimina_cliente TO 'Personale-Segreteria'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_cliente TO 'Personale-Segreteria'@'localhost';


-- PRIVILEGI TITOLARE
GRANT SELECT ON owlbreak.operatore TO 'Titolare'@'localhost';
GRANT SELECT ON owlbreak.ordine TO 'Titolare'@'localhost';
GRANT SELECT ON owlbreak.prodotto TO 'Titolare'@'localhost';
GRANT SELECT ON owlbreak.composizione TO 'Titolare'@'localhost';
GRANT SELECT ON owlbreak.ingrediente TO 'Titolare'@'localhost';
GRANT SELECT ON owlbreak.rifornimento TO 'Titolare'@'localhost';
GRANT SELECT ON owlbreak.fornitore TO 'Titolare'@'localhost';


-- PRIVILEGI ADDETTI VENDITE
GRANT SELECT ON owlbreak.ordine TO 'Addetto-vendite'@'localhost';
GRANT SELECT ON owlbreak.prodotto TO 'Addetto-vendite'@'localhost';
GRANT SELECT ON owlbreak.composizione TO 'Addetto-vendite'@'localhost';
GRANT SELECT ON owlbreak.ingrediente TO 'Addetto-vendite'@'localhost';
GRANT SELECT ON owlbreak.rifornimento TO 'Addetto-vendite'@'localhost';
GRANT SELECT ON owlbreak.fornitore TO 'Addetto-vendite'@'localhost';

-- PRIVILEGI ADDETTI CCONSEGNE
GRANT SELECT ON owlbreak.ordine TO 'Addetto-consegne'@'localhost';
GRANT SELECT ON owlbreak.consegna TO 'Addetto-consegne'@'localhost';


-- PRIVILEGI FORNITORI
GRANT SELECT ON owlbreak.rifornimento TO 'Fornitore'@'localhost';