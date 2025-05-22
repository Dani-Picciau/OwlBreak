-- ***** CREAZIONE USER *****

CREATE USER 'Studente'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Personale-Docente'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Personale-Ata'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Personale-Segreteria'@'localhost' IDENTIFIED BY 'Cliente';
CREATE USER 'Titolare'@'localhost' IDENTIFIED BY 'Operatore';
CREATE USER 'Addetto-Consegne'@'localhost' IDENTIFIED BY 'Operatore';
CREATE USER 'Addetto-Vendite'@'localhost' IDENTIFIED BY 'Operatore';
CREATE USER 'Fornitore'@'localhost' IDENTIFIED BY 'Fornitore';

-- Creazione Database
CREATE DATABASE owlbreak;

USE owlbreak;

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
            'Addetto-Consegne',
            'Addetto-Vendite'
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

CREATE TABLE consegna (
    luogoConsegna VARCHAR(100) PRIMARY KEY,
    OperatoreID INT,
    FOREIGN KEY (OperatoreID) REFERENCES Operatore(CodiceID)
);

#passw: "Pluto_paperino12" per tutti i clienti, gli operatori e i fornitori
INSERT INTO cliente (email, passw, nome, cognome, luogoConsegna, tipoCliente) VALUES
('m.rossi@studenti.boscogrigio.it', '$2y$10$HVcL3DiZzf37uZMG7XtSUuigqqzdMBfzi4GVdMeQk78RbUm9Rj5dS', 'Marco', 'Rossi', '1A', 'Studente'), 
('e.verdi@studenti.boscogrigio.it', '$2y$10$nDnqR2jYhnvb9T9xOLnBg.D4QkVKXIsbP3UGkcjmfiQvzsqEOd5He', 'Elisa', 'Verdi', '1A', 'Studente'), 
('a.neri@studenti.boscogrigio.it', '$2y$10$e8MN73RHAT.mueP4MSICv.zr/fSgkusMxrQ/CVJQeP3a8zN1y8P6m', 'Anna', 'Neri', '1A', 'Studente'), 
('f.rossi@studenti.boscogrigio.it', '$2y$10$e8ZAXMFUkeunbDsWsT7g9eItn/CdbiZV4eZ1OjCYVgMzNnmRuIzTu', 'Francesco', 'Rossi', '1A', 'Studente'), 
('l.bianchi@studenti.boscogrigio.it', '$2y$10$56.XwKJhvdz.3sBOfmzrIeoUqNnrIHKe.TbPu/rSYqkPMPFfwpKzW', 'Luca', 'Bianchi', '1A', 'Studente'), 

('e.verdi1@studenti.boscogrigio.it', '$2y$10$6QvwA/asaUwHdGlnAXcv7uR2BkerpUzIfk1nd2K5H3pgjNcdt/kVq', 'Eleonora', 'Verdi', '2A', 'Studente'),
('m.rossi1@studenti.boscogrigio.it', '$2y$10$Dvc0uMIes.L.HqUAAPok9uqD12T0r.9MqNBUz5bmMN6BsoQ5Q.i0e', 'Michele', 'Rossi', '2A', 'Studente'),
('a.sanna@studenti.boscogrigio.it', ' $2y$10$FxYPMXOVya99nbzk9/cXW.GlsPxK4ZeYinZlD4El3CxZUeKMI77TG', 'Alessandro', 'Sanna', '2A', 'Studente'),
('f.masala@studenti.boscogrigio.it', '$2y$10$DtuP7UrHWV0376uUf0Zvfup4ZxH7I4YXT.2uUvWi8eEmkpFtqSOEq', 'Flavio', 'Masala', '2A', 'Studente'),
('m.secchi@studenti.boscogrigio.it', '$2y$10$DVGaYYRSSlz7mXbWbCs4suZlRGrqxuQ5k8QZySGctrPG5PyVpKEx2', 'Miriam', 'Secchi', '2A', 'Studente'),

('g.pinna@studenti.boscogrigio.it', '$2y$10$JXL9Nb0cy5FWBhowqfD05uZoLhuZ4Kfmf4kOvZ8hSzxMYzXWobvH.', 'Giorgio', 'Pinna', '3A', 'Studente'),
('g.manai@studenti.boscogrigio.it', '$2y$10$bPUjE1gSqGGyJesqAjmiw.1ZsGK.p1p9/F9qUR0WPbQ7zIWbTnmqW', 'Giancarlo', 'Manai', '3A', 'Studente'),
('m.neri@studenti.boscogrigio.it', ' $2y$10$.7PTuXHs8D6.46TpFRk.SONNoY36H/7byPKKwPyKdJAcqoNZZdDCe', 'Mario', 'Neri', '3A', 'Studente'),
('g.melis@studenti.boscogrigio.it', '$2y$10$5FM28TXJpb8r6TGzCqMDY.776YSnOeKrCOqVlKAXQUt/bBFn.mskG', 'Giorgia', 'Melis', '3A', 'Studente'),
('a.sanna1@studenti.boscogrigio.it', '$2y$10$XLEtl.jAyey4NucakymJFOtigu/DJVWgsxG733Ix18QH28jxjNGhO', 'Aurora', 'Sanna', '3A', 'Studente'),

('d.picciau@studenti.boscogrigio.it', '$2y$10$uDjFQdBT7awiJPJo/N8MMeomTiW9TuGAesEJisRMlzrMg6KaWx8j2', 'Daniele', 'Picciau', '4A', 'Studente'),
('m.manai@studenti.boscogrigio.it', '$2y$10$0EUI7akpTQSOX9smUIHKQeGKFdrKLeE/CSyfAxt5pX8bqSY23TwaG', 'Monica', 'Manai', '4A', 'Studente'),
('m.pedoni@studenti.boscogrigio.it', '$2y$10$829KIxpgXB/vX6cD9Q33Hu3Q4hNzZ3514yOOlpbDgRLI1m4LM0.Je', 'Matteo', 'Pedoni', '4A', 'Studente'),
('a.fiori@studenti.boscogrigio.it', '$2y$10$VPUfba/YH7yu5QjbT0M2eOViEL3S72TQ9KgkXaSY3a9MTTU0bBGlW', 'Andrea', 'Fiori', '4A', 'Studente'),
('d.perazzona@studenti.boscogrigio.it', '$2y$10$JaWsT8LzgSsCwvmQRZYDs.vydZ/4mY/V69/QqSVpMU859Q79ZCzD6', 'Diego', 'Perazzona', '4A', 'Studente'),

('a.sanna2@studenti.boscogrigio.it', '$2y$10$6N0OyR93FO3FHjo11lIW9ujilYi/GSSX4kYqjKcEJvrn6DzZ4Gz9C', 'Anna', 'Sanna', '5A', 'Studente'),
('f.ruiu@studenti.boscogrigio.it', '$2y$10$kvEFGMZs7mlEjqjsALLf8uimcFYtBfMG1Ye9toF0syDIA2yPXSBmG', 'Franco', 'Ruiu', '5A', 'Studente'),
('g.bianchi@studenti.boscogrigio.it', '$2y$10$RXREyta6cgSF1k69U3ci0eVC0m2SFxClUjfOlRRCgeptNizWwawMS', 'Giacomo', 'Bianchi', '5A', 'Studente'),
('g.esposito@studenti.boscogrigio.it', '$2y$10$RIWVK2deRX7EGRBPpXUrdetQDXzM192g7Puu7noFNaz/1cD1XyN8C', 'Giulia', 'Esposito', '5A', 'Studente'),
('a.leonardi@studenti.boscogrigio.it', '$2y$10$wKih28KFbqE2IfNkeL/yYON86.jlip4YwDFP2lLR0zScoRXj3jGiC', 'Azzurra', 'Leonardi', '5A', 'Studente'),

('l.bianchi@boscogrigio.it', '$2y$10$6MtUBKEcJ/FcMmtLq3cMVuTDC.1sF7K3lvrSJvRKArh02MNwxifeC', 'Luca', 'Bianchi', 'Aula docenti', 'Personale-Docente'),
('l.riva@boscogrigio.it', '$2y$10$ebNdOxTPSIPBZPt8fogRFOZLnGCX63129TauQnQtfTSWWfcOQ0ruK', 'Lorenzo', 'Riva', 'Aula docenti', 'Personale-Docente'),
('l.sanna@boscogrigio.it', '$2y$10$jZZD3SGfPeLo2UYTUaysoOtBMWFzPZUhdEEwyfl1/zbO4rQzNx8A6', 'Lucrezia', 'Sanna', 'Aula docenti', 'Personale-Docente'),
('a.lagorio@boscogrigio.it', '$2y$10$S854pCaFxe9IMaEciRZGxuX.cbpO2eqNnuBqVzMkZCID9mcBBc0ym', 'Andrea', 'Lagorio', 'Aula docenti', 'Personale-Docente'),
('r.delussu@boscogrigio.it', '$2y$10$r8dN3vpe4Chwmv2L79Gg0ur2qZEYJ.7.AA07StdgCrgDOEBZQdwky', 'Rita', 'Delussu', 'Aula docenti', 'Personale-Docente'),

('g.delogu@boscogrigio.it', '$2y$10$4DmMYeTur2K75WFZaDFZl.Qq7p1h0hT1wA/5WO5hOtPqGK4UJ.Tta', 'Grazia', 'Delogu', 'Segreteria centrale', 'Personale-Ata'),
('g.meloni@boscogrigio.it', '$2y$10$fjwGpYWjUZEvkrRjhnkXlOQpdPu19qkAiiLk3XBid6DletdAuPeLO', 'Giorgia', 'Meloni', 'Segreteria-1P', 'Personale-Ata'),
('m.salvini@boscogrigio.it', '$2y$10$dRMByMIhD0kbe6j6W1oWAO728uQGuluxocWKILJfgLtwByNYtbr12', 'Matteo', 'Salvini', 'Segreteria-2P', 'Personale-Ata'),

('l.brodo@boscogrigio.it', '$2y$10$0WpgVFNAWJcle1HlYK2N0.adApv2GdoA/Eaapo./oekhs4Z3OpwQe', 'Linda', 'Brodo', 'Segreteria centrale', 'Personale-Segreteria'),
('l.ghiani@boscogrigio.it', '$2y$10$74DkOfLl4d46UlhmGRs0vuldzSsPwQDCsAWgQYDWCEROlBG/uRrNe', 'Luca', 'Ghiani', 'Segreteria centrale', 'Personale-Segreteria');

INSERT INTO prodotto (nome, prezzo, disponibilità) VALUES
('Acqua naturale', 0.50, TRUE),
('Acqua gasata', 0.60, TRUE),
('Coca Cola', 1.50, TRUE),
('Fanta', 1.50, TRUE),
('The freddo limone', 1.50, TRUE),
('The freddo pesca', 1.50, TRUE),
('The freddo verde', 1.50, TRUE),
('Caffè espresso', 1.00, TRUE),
('Caffè macchiato', 1.20, TRUE),
('Ginseng', 1.20, TRUE),
('Latte macchiato', 1.50, TRUE),
('The caldo', 1.20, TRUE),
('Succo di pera', 1.50, TRUE),
('Succo di pesca', 1.50, TRUE),
('Succo ACE', 1.50, TRUE),
('Spremuta d\'arancia', 2.00, TRUE),

('Panino al prosciutto cotto', 1.50, TRUE),
('Panino al prosciutto cotto con fontina', 1.80, TRUE),
('Panino al salame', 1.50, TRUE),
('Panino al salame con fontina', 1.80, TRUE),
('Panino con mortadella', 1.30, TRUE),
('Panino con mortadella e fontina', 1.60, TRUE),
('Panino con prosciutto crudo', 1.70, TRUE),
('Panino con prosciutto crudo e fontina', 2.00, TRUE),

('Salsa senape', 0.20, TRUE),
('Salsa maionese', 0.20, TRUE),
('Salsa rosa', 0.20, TRUE),
('Salsa ketchup', 0.20, TRUE),

('Muffin al cioccolato', 1.50, TRUE),
('Muffin con gocce di cioccolato', 1.50, TRUE),
('Muffin classico', 1.30, TRUE),
('Pizzetta sfoglia cagliaritana', 1.80, TRUE),
('Crostata alle mele', 1.80, TRUE),
('Crostata alla Nutella', 2.00, TRUE),
('Torta allo yogurt', 2.00, TRUE),
('Torta di mele', 2.00, TRUE),
('Torta al cioccolato', 2.00, TRUE),
('Cornetto alla marmellata', 1.20, TRUE),
('Cornetto al cioccolato', 1.20, TRUE),
('Cornetto al pistacchio', 1.30, TRUE),
('Cornetto alla crema', 1.20, TRUE),
('Cornetto ai frutti di bosco', 1.20, TRUE),
('Frittella con buco', 1.00, TRUE),
('Frittella alla crema', 1.20, TRUE),

('Patatine classiche', 1.00, TRUE),
('Patatine rustiche', 1.00, TRUE),
('Patatine alla paprika', 1.00, TRUE),
('Patatine piccanti', 1.00, TRUE),
('Croccantelle gusto pizza', 0.80, TRUE),
('Croccantelle gusto bacon', 0.80, TRUE),
('Schiacciatine al rosmarino', 0.90, TRUE),
('Schiacciatine alle olive', 0.90, TRUE),
('Pringles classiche', 1.20, TRUE),
('Pringles piccanti', 1.20, TRUE),
('Cingomme AIR', 0.60, TRUE),
('Cingomme Vivident', 0.60, TRUE),
('Big Babol', 0.70, TRUE),
('Chupachups assortiti', 0.50, TRUE);

INSERT INTO operatore (CodiceID, email, passw, nome, cognome, ruolo) VALUES
(1,'c.ricci@owlbreak.it', '$2y$10$Wlx65vccn1syma59MRcAfeaE79kFJGsqV7xY0sLhtjx4C.rXaU5IK', 'Cristina', 'Ricci', 'Titolare'), 
(2,'m.cau@owlbreak.it', '$2y$10$rV1.NT9IJ0ZhTI19aGa8Huc2LuMtmLnu3fQXBacqBgLG5kbPzE0SO', 'Michela', 'Cau', 'Addetto-Consegne'),
(3,'m.ferrari@owlbreak.it', '$2y$10$uD9F.ScRCSrwdbFjkjjgoOTezwS1QCU7.ab0ZxjQf7wEKeUxAnEpi', 'Marco', 'Ferrari', 'Addetto-Vendite'),
(4,'m.romano@owlbreak.it', '$2y$10$1FedTJ/UGefggQVyb17UkeFB8Typof3OFkpLlOXqOVMyHsV1ZnXya', 'Maria Grazia', 'Romano', 'Addetto-Vendite'),
(5,'e.serra@owlbreak.it', '$2y$10$kgoUKF.d1MEjWKVyEngRjuDZ6f3cbVrdeHy7gy24SuQsazGKGQPSG', 'Elena', 'Serra', 'Addetto-Consegne');

INSERT INTO ordine(data, ora, emailCliente, nomeProdotto, consegnato, quantità, OperatoreID) VALUES
('2025-04-26', '9:54:34', 'm.rossi@studenti.boscogrigio.it', 'The freddo verde', TRUE, 1, 2),
('2025-05-03', '9:07:38', 'm.rossi@studenti.boscogrigio.it', 'Acqua naturale', TRUE, 3, 2),
('2025-05-03', '8:20:00', 'm.rossi@studenti.boscogrigio.it', 'Cingomme AIR', FALSE, 2, 2),

('2025-05-03', '9:09:45', 'e.verdi@studenti.boscogrigio.it', 'Crostata alla Nutella', FALSE, 1, 5),
('2025-04-26', '9:06:03', 'e.verdi@studenti.boscogrigio.it', 'The caldo', TRUE, 1, 5),
('2025-04-26', '8:36:54', 'e.verdi@studenti.boscogrigio.it', 'Torta di mele', TRUE, 3, 5),

('2025-04-26', '8:42:32', 'a.neri@studenti.boscogrigio.it', 'Cornetto alla marmellata', TRUE, 1, 2),
('2025-05-03', '9:32:57', 'a.neri@studenti.boscogrigio.it', 'Fanta', FALSE, 1, 5),
('2025-05-03', '8:53:35', 'a.neri@studenti.boscogrigio.it', 'Panino con prosciutto crudo', FALSE, 2, 5),

('2025-05-03', '9:07:49', 'f.rossi@studenti.boscogrigio.it', 'Panino al prosciutto cotto con fontina', TRUE, 3, 2),
('2025-04-26', '8:44:52', 'f.rossi@studenti.boscogrigio.it', 'Acqua naturale', TRUE, 3, 5),
('2025-04-26', '8:24:55', 'f.rossi@studenti.boscogrigio.it', 'Panino con prosciutto crudo', TRUE, 2, 5),

('2025-04-26', '8:50:06', 'l.bianchi@studenti.boscogrigio.it', 'The freddo pesca', TRUE, 1, 2),
('2025-04-26', '8:54:36', 'l.bianchi@studenti.boscogrigio.it', 'The freddo limone', TRUE, 3, 5),
('2025-04-26', '9:11:27', 'l.bianchi@studenti.boscogrigio.it', 'Pringles piccanti', TRUE, 3, 5),

('2025-05-03', '9:34:25', 'e.verdi1@studenti.boscogrigio.it', 'Crostata alle mele', FALSE, 3, 5),
('2025-04-26', '9:55:49', 'e.verdi1@studenti.boscogrigio.it', 'Panino con mortadella', TRUE, 3, 2),
('2025-05-03', '9:41:50', 'e.verdi1@studenti.boscogrigio.it', 'Panino al salame con fontina', FALSE, 2, 2),

('2025-05-03', '8:14:17', 'm.rossi1@studenti.boscogrigio.it', 'Cornetto ai frutti di bosco', TRUE, 1, 2),
('2025-05-03', '9:35:10', 'm.rossi1@studenti.boscogrigio.it', 'Panino al salame', TRUE, 3, 2),
('2025-04-26', '9:04:08', 'm.rossi1@studenti.boscogrigio.it', 'Panino al salame con fontina', TRUE, 1, 5),

('2025-04-26', '8:14:47', 'a.sanna@studenti.boscogrigio.it', 'Patatine alla paprika', TRUE, 2, 2),
('2025-05-03', '8:20:40', 'a.sanna@studenti.boscogrigio.it', 'Croccantelle gusto bacon', FALSE, 3, 5),
('2025-04-26', '8:49:47', 'a.sanna@studenti.boscogrigio.it', 'Torta al cioccolato', TRUE, 3, 2),

('2025-05-03', '8:40:38', 'f.masala@studenti.boscogrigio.it', 'Cornetto al pistacchio', TRUE, 3, 3),
('2025-04-26', '8:41:35', 'f.masala@studenti.boscogrigio.it', 'Caffè macchiato', TRUE, 1, 4),
('2025-05-03', '8:47:40', 'f.masala@studenti.boscogrigio.it', 'Patatine piccanti', TRUE, 1, 4),

('2025-05-03', '9:20:51', 'm.secchi@studenti.boscogrigio.it', 'Patatine piccanti', TRUE, 3, 4),
('2025-04-26', '9:26:33', 'm.secchi@studenti.boscogrigio.it', 'Schiacciatine alle olive', TRUE, 3, 3),
('2025-05-03', '9:29:38', 'm.secchi@studenti.boscogrigio.it', 'Muffin con gocce di cioccolato', FALSE, 2, 1),

('2025-05-03', '8:30:55', 'g.pinna@studenti.boscogrigio.it', 'Cingomme AIR', FALSE, 2, 3),
('2025-05-03', '9:30:43', 'g.pinna@studenti.boscogrigio.it', 'Coca Cola', TRUE, 2, 4),
('2025-04-26', '8:37:42', 'g.pinna@studenti.boscogrigio.it', 'Schiacciatine al rosmarino', TRUE, 2, 4),

('2025-04-26', '8:15:56', 'g.manai@studenti.boscogrigio.it', 'Panino con prosciutto crudo e fontina', TRUE, 2, 4),
('2025-05-03', '8:23:49', 'g.manai@studenti.boscogrigio.it', 'Panino al salame con fontina', FALSE, 1, 3),
('2025-05-03', '8:04:29', 'g.manai@studenti.boscogrigio.it', 'Schiacciatine al rosmarino', TRUE, 1, 1),

('2025-04-26', '8:40:59', 'm.neri@studenti.boscogrigio.it', 'Panino al prosciutto cotto', TRUE, 3, 3),
('2025-05-03', '8:12:21', 'm.neri@studenti.boscogrigio.it', 'Chupachups assortiti', TRUE, 1, 4),
('2025-05-03', '9:00:13', 'm.neri@studenti.boscogrigio.it', 'Succo di pera', TRUE, 2, 1),

('2025-05-03', '8:33:44', 'g.melis@studenti.boscogrigio.it', 'Acqua gasata', FALSE, 2, 3),
('2025-05-03', '8:13:11', 'g.melis@studenti.boscogrigio.it', 'Panino al prosciutto cotto', TRUE, 1, 3),
('2025-05-03', '9:57:19', 'g.melis@studenti.boscogrigio.it', 'Muffin con gocce di cioccolato', FALSE, 1, 1),

('2025-05-03', '8:35:15', 'a.sanna1@studenti.boscogrigio.it', 'Cornetto al cioccolato', FALSE, 3, 3),
('2025-04-26', '9:28:32', 'a.sanna1@studenti.boscogrigio.it', 'Panino al prosciutto cotto con fontina', TRUE, 1, 4),
('2025-04-26', '9:28:04', 'a.sanna1@studenti.boscogrigio.it', 'Cingomme AIR', TRUE, 2, 1),


('2025-05-03', '8:52:20', 'd.picciau@studenti.boscogrigio.it', 'Chupachups assortiti', TRUE, 2, 4),
('2025-05-03', '9:00:26', 'd.picciau@studenti.boscogrigio.it', 'Acqua naturale', FALSE, 1, 1),
('2025-05-03', '9:00:26', 'd.picciau@studenti.boscogrigio.it', 'Schiacciatine al rosmarino', FALSE, 1, 1),
('2025-04-26', '8:31:47', 'd.picciau@studenti.boscogrigio.it', 'Panino al salame', TRUE, 2, 3),
('2025-04-26', '9:53:13', 'd.picciau@studenti.boscogrigio.it', 'Schiacciatine al rosmarino', TRUE, 2, 1),
('2025-04-24', '8:09:33', 'd.picciau@studenti.boscogrigio.it', 'Torta di mele', TRUE, 2, 4),
('2025-04-17', '8:17:56', 'd.picciau@studenti.boscogrigio.it', 'Acqua naturale', TRUE, 1, 3),
('2025-04-17', '8:17:56', 'd.picciau@studenti.boscogrigio.it', 'Pizzetta sfoglia cagliaritana', TRUE, 3, 3),

('2025-05-03', '9:40:54', 'm.manai@studenti.boscogrigio.it', 'Patatine rustiche', FALSE, 1, 3),
('2025-05-03', '8:53:18', 'm.manai@studenti.boscogrigio.it', 'Cingomme AIR', FALSE, 3, 1),
('2025-04-26', '9:24:47', 'm.manai@studenti.boscogrigio.it', 'Patatine alla paprika', TRUE, 1, 1),

('2025-04-26', '9:02:28', 'm.pedoni@studenti.boscogrigio.it', 'Succo ACE', TRUE, 1, 3),
('2025-05-03', '9:19:08', 'm.pedoni@studenti.boscogrigio.it', 'Patatine piccanti', FALSE, 2, 3),
('2025-05-03', '9:27:59', 'm.pedoni@studenti.boscogrigio.it', 'Succo di pesca', TRUE, 1, 4),

('2025-05-03', '9:48:09', 'a.fiori@studenti.boscogrigio.it', 'Croccantelle gusto pizza', TRUE, 1, 1),
('2025-04-26', '8:31:51', 'a.fiori@studenti.boscogrigio.it', 'Muffin con gocce di cioccolato', TRUE, 1, 4),
('2025-04-26', '8:35:50', 'a.fiori@studenti.boscogrigio.it', 'Cornetto al cioccolato', TRUE, 2, 3),

('2025-04-26', '8:25:28', 'd.perazzona@studenti.boscogrigio.it', 'Coca Cola', TRUE, 2, 1),
('2025-05-03', '8:11:32', 'd.perazzona@studenti.boscogrigio.it', 'The freddo limone', TRUE, 2, 4),
('2025-05-03', '9:34:49', 'd.perazzona@studenti.boscogrigio.it', 'Schiacciatine al rosmarino', TRUE, 3, 1),

('2025-05-03', '8:42:06', 'a.sanna2@studenti.boscogrigio.it', 'Latte macchiato', FALSE, 2, 3),
('2025-05-03', '8:05:09', 'a.sanna2@studenti.boscogrigio.it', 'Patatine rustiche', FALSE, 2, 4),
('2025-04-26', '9:24:48', 'a.sanna2@studenti.boscogrigio.it', 'Patatine rustiche', TRUE, 3, 1),

('2025-05-03', '8:14:28', 'f.ruiu@studenti.boscogrigio.it', 'The caldo', FALSE, 3, 3),
('2025-04-26', '9:19:21', 'f.ruiu@studenti.boscogrigio.it', 'Muffin al cioccolato', TRUE, 1, 4),
('2025-05-03', '8:28:09', 'f.ruiu@studenti.boscogrigio.it', 'Cornetto al pistacchio', FALSE, 3, 4),

('2025-04-26', '9:30:59', 'g.bianchi@studenti.boscogrigio.it', 'Cornetto alla marmellata', TRUE, 2, 3),
('2025-04-26', '8:59:04', 'g.bianchi@studenti.boscogrigio.it', 'The freddo verde', TRUE, 2, 3),
('2025-04-26', '9:10:02', 'g.bianchi@studenti.boscogrigio.it', 'Acqua gasata', TRUE, 2, 3),

('2025-05-03', '9:11:45', 'g.esposito@studenti.boscogrigio.it', 'Succo di pera', TRUE, 3, 3),
('2025-04-26', '9:57:51', 'g.esposito@studenti.boscogrigio.it', 'Chupachups assortiti', TRUE, 3, 3),
('2025-04-26', '8:06:51', 'g.esposito@studenti.boscogrigio.it', 'Cornetto ai frutti di bosco', TRUE, 2, 1),

('2025-05-03', '9:12:17', 'a.leonardi@studenti.boscogrigio.it', 'Fanta', TRUE, 1, 4),
('2025-04-26', '8:36:02', 'a.leonardi@studenti.boscogrigio.it', 'The freddo limone', TRUE, 3, 1),
('2025-05-03', '8:27:44', 'a.leonardi@studenti.boscogrigio.it', 'Spremuta d\'arancia', FALSE, 3, 3),

('2025-05-03', '8:25:56', 'l.bianchi@boscogrigio.it', 'The freddo verde', TRUE, 1, 4),
('2025-04-26', '9:41:57', 'l.bianchi@boscogrigio.it', 'Latte macchiato', TRUE, 2, 4),
('2025-05-03', '8:06:28', 'l.bianchi@boscogrigio.it', 'Patatine classiche', TRUE, 1, 1),

('2025-04-26', '9:48:01', 'l.riva@boscogrigio.it', 'Fanta', TRUE, 3, 1),
('2025-04-26', '8:51:45', 'l.riva@boscogrigio.it', 'Schiacciatine al rosmarino', TRUE, 3, 4),
('2025-04-26', '8:17:32', 'l.riva@boscogrigio.it', 'Torta allo yogurt', TRUE, 2, 4),

('2025-04-26', '9:42:11', 'l.sanna@boscogrigio.it', 'Cingomme Vivident', TRUE, 2, 3),
('2025-05-03', '8:46:00', 'l.sanna@boscogrigio.it', 'Cornetto al pistacchio', TRUE, 2, 1),
('2025-04-26', '8:34:51', 'l.sanna@boscogrigio.it', 'Muffin classico', TRUE, 1, 3),

('2025-05-03', '8:32:25', 'a.lagorio@boscogrigio.it', 'Panino con mortadella e fontina', TRUE, 3, 3),
('2025-04-26', '9:45:41', 'a.lagorio@boscogrigio.it', 'Torta allo yogurt', TRUE, 1, 1),
('2025-04-26', '9:59:59', 'a.lagorio@boscogrigio.it', 'Cornetto alla crema', TRUE, 2, 3),

('2025-05-03', '8:50:52', 'r.delussu@boscogrigio.it', 'Coca Cola', FALSE, 3, 1),
('2025-04-26', '9:31:00', 'r.delussu@boscogrigio.it', 'Panino al salame', TRUE, 3, 1),
('2025-04-26', '9:35:00', 'r.delussu@boscogrigio.it', 'Coca Cola', TRUE, 2, 1),

('2025-05-03', '9:52:40', 'g.delogu@boscogrigio.it', 'Panino con prosciutto crudo e fontina', FALSE, 3, 3),
('2025-05-03', '9:39:35', 'g.delogu@boscogrigio.it', 'Cornetto ai frutti di bosco', FALSE, 1, 1),
('2025-04-26', '8:59:46', 'g.delogu@boscogrigio.it', 'Patatine piccanti', TRUE, 3, 1),

('2025-04-26', '9:47:53', 'g.meloni@boscogrigio.it', 'Cornetto ai frutti di bosco', TRUE, 1, 4),
('2025-05-03', '9:18:01', 'g.meloni@boscogrigio.it', 'Panino con prosciutto crudo', TRUE, 3, 4),
('2025-05-03', '9:52:45', 'g.meloni@boscogrigio.it', 'The caldo', TRUE, 1, 1),

('2025-05-03', '9:29:41', 'm.salvini@boscogrigio.it', 'Succo ACE', TRUE, 3, 4),
('2025-05-03', '9:32:40', 'm.salvini@boscogrigio.it', 'Panino al prosciutto cotto', TRUE, 1, 3),
('2025-05-03', '8:30:52', 'm.salvini@boscogrigio.it', 'Acqua naturale', TRUE, 3, 1),

('2025-05-03', '9:14:55', 'l.brodo@boscogrigio.it', 'Coca Cola', FALSE, 2, 3),
('2025-05-03', '9:00:26', 'l.brodo@boscogrigio.it', 'Acqua naturale', FALSE, 1, 1),
('2025-05-03', '9:00:26', 'l.brodo@boscogrigio.it', 'Schiacciatine al rosmarino', FALSE, 1, 1),
('2025-04-26', '9:18:45', 'l.brodo@boscogrigio.it', 'Patatine piccanti', TRUE, 1, 1),
('2025-04-26', '8:10:06', 'l.brodo@boscogrigio.it', 'Muffin con gocce di cioccolato', TRUE, 2, 1),
('2025-04-23', '8:31:47', 'l.brodo@boscogrigio.it', 'Panino al salame', TRUE, 2, 3),
('2025-04-17', '8:09:33', 'l.brodo@boscogrigio.it', 'Torta di mele', TRUE, 2, 4),
('2025-04-15', '8:17:56', 'l.brodo@boscogrigio.it', 'Acqua naturale', TRUE, 1, 3),
('2025-04-15', '8:17:56', 'l.brodo@boscogrigio.it', 'Pizzetta sfoglia cagliaritana', TRUE, 3, 3),

('2025-04-26', '8:55:24', 'l.ghiani@boscogrigio.it', 'Muffin classico', TRUE, 3, 1),
('2025-05-03', '9:40:13', 'l.ghiani@boscogrigio.it', 'Ginseng', FALSE, 2, 1),
('2025-04-26', '8:48:14', 'l.ghiani@boscogrigio.it', 'Fanta', TRUE, 2, 4);


INSERT INTO ingrediente (nome, allergeni, quantità) VALUES
('Acqua naturale', '', 1500),
('Acqua gasata', '', 1500),
('Coca Cola', '', 1000),
('Fanta', '', 1000),
('The freddo limone', '', 1000),
('The freddo pesca', '', 1000),
('The freddo verde', '', 1000),
('Caffè macinato', '', 100),
('Latte intero', 'Latte', 1000),
('Polvere ginseng', '', 100),
('Foglie di the', '', 50),
('Arance', '', 500),
('Succo di pera', '', 1000),
('Succo di pesca', '', 1000),
('Succo ACE', '', 1000),

('Pane per panini', 'Glutine, Uova', 800),
('Prosciutto cotto', '', 250),
('Salame', '', 250),
('Mortadella', 'Latte', 200),
('Prosciutto crudo', '', 250),
('Fontina', 'Latte', 300),

('Maionese', 'Uova, Senape', 200),
('Ketchup', '', 150),
('Salsa rosa', 'Uova', 150),
('Senape', 'Senape', 150),

('Muffin al cioccolato', 'Glutine, Uova, Latte, Soia', 50),
('Muffin con gocce di cioccolato', 'Glutine, Uova, Latte, Soia', 50),
('Muffin classico', 'Glutine, Uova, Latte', 50),
('Pizzetta sfoglia cagliaritana', 'Glutine', 50),
('Crostata alle mele', 'Glutine, Uova, Latte', 40),
('Crostata alla Nutella', 'Glutine, Uova, Latte, Frutta a guscio', 40),
('Torta allo yogurt', 'Glutine, Latte, Uova', 50),
('Torta di mele', 'Glutine, Latte, Uova', 40),
('Torta al cioccolato', 'Glutine, Latte, Uova, Soia', 50),
('Cornetto alla marmellata', 'Glutine, Latte, Uova', 100),
('Cornetto al cioccolato', 'Glutine, Latte, Uova, Soia', 100),
('Cornetto al pistacchio', 'Glutine, Latte, Uova, Frutta a guscio', 100),
('Cornetto alla crema', 'Glutine, Latte, Uova', 100),
('Cornetto ai frutti di bosco', 'Glutine, Latte, Uova', 100),
('Frittella con buco', 'Glutine, Latte, Uova', 100),
('Frittella alla crema', 'Glutine, Latte, Uova', 100),

('Patatine classiche', '', 100),
('Patatine rustiche', '', 100),
('Patatine alla paprika', '', 100),
('Patatine piccanti', '', 100),
('Croccantelle gusto pizza', 'Glutine', 100),
('Croccantelle gusto bacon', 'Glutine', 100),
('Schiacciatine al rosmarino', 'Glutine', 100),
('Schiacciatine alle olive', 'Glutine', 100),
('Pringles classiche', '', 100),
('Pringles piccanti', '', 100),
('Cingomme AIR', '', 50),
('Cingomme Vivident', '', 50),
('Big Babol', '', 50),
('Chupachups assortiti', '', 50);

INSERT INTO composizione (nomeProdotto, nomeIngrediente) VALUES
('Acqua naturale', 'Acqua naturale'),
('Acqua gasata', 'Acqua gasata'),
('Coca Cola', 'Coca Cola'),
('Fanta', 'Fanta'),
('The freddo limone', 'The freddo limone'),
('The freddo pesca', 'The freddo pesca'),
('The freddo verde', 'The freddo verde'),
('Succo di pera', 'Succo di pera'),
('Succo di pesca', 'Succo di pesca'),
('Succo ACE', 'Succo ACE'),
('Spremuta d\'arancia', 'Arance'),
('The caldo', 'Foglie di the'),
('The caldo', 'Acqua naturale'),
('Caffè espresso', 'Caffè macinato'),
('Caffè macchiato', 'Caffè macinato'),
('Caffè macchiato', 'Latte intero'),
('Latte macchiato', 'Latte intero'),
('Latte macchiato', 'Caffè macinato'),
('Ginseng', 'Polvere ginseng'),
('Ginseng', 'Latte intero'),

('Panino al prosciutto cotto', 'Pane per panini'),
('Panino al prosciutto cotto', 'Prosciutto cotto'),
('Panino al prosciutto cotto con fontina', 'Pane per panini'),
('Panino al prosciutto cotto con fontina', 'Prosciutto cotto'),
('Panino al prosciutto cotto con fontina', 'Fontina'),

('Panino al salame', 'Pane per panini'),
('Panino al salame', 'Salame'),
('Panino al salame con fontina', 'Pane per panini'),
('Panino al salame con fontina', 'Salame'),
('Panino al salame con fontina', 'Fontina'),

('Panino con mortadella', 'Pane per panini'),
('Panino con mortadella', 'Mortadella'),
('Panino con mortadella e fontina', 'Pane per panini'),
('Panino con mortadella e fontina', 'Mortadella'),
('Panino con mortadella e fontina', 'Fontina'),

('Panino con prosciutto crudo', 'Pane per panini'),
('Panino con prosciutto crudo', 'Prosciutto crudo'),
('Panino con prosciutto crudo e fontina', 'Pane per panini'),
('Panino con prosciutto crudo e fontina', 'Prosciutto crudo'),
('Panino con prosciutto crudo e fontina', 'Fontina'),

('Salsa senape', 'Senape'),
('Salsa maionese', 'Maionese'),
('Salsa rosa', 'Salsa rosa'),
('Salsa ketchup', 'Ketchup'),

('Muffin al cioccolato', 'Muffin al cioccolato'),
('Muffin con gocce di cioccolato', 'Muffin con gocce di cioccolato'),
('Muffin classico', 'Muffin classico'),
('Pizzetta sfoglia cagliaritana', 'Pizzetta sfoglia cagliaritana'),
('Crostata alle mele', 'Crostata alle mele'),
('Crostata alla Nutella', 'Crostata alla Nutella'),
('Torta allo yogurt', 'Torta allo yogurt'),
('Torta di mele', 'Torta di mele'),
('Torta al cioccolato', 'Torta al cioccolato'),
('Cornetto alla marmellata', 'Cornetto alla marmellata'),
('Cornetto al cioccolato', 'Cornetto al cioccolato'),
('Cornetto al pistacchio', 'Cornetto al pistacchio'),
('Cornetto alla crema', 'Cornetto alla crema'),
('Cornetto ai frutti di bosco', 'Cornetto ai frutti di bosco'),
('Frittella con buco', 'Frittella con buco'),
('Frittella alla crema', 'Frittella alla crema'),

('Patatine classiche', 'Patatine classiche'),
('Patatine rustiche', 'Patatine rustiche'),
('Patatine alla paprika', 'Patatine alla paprika'),
('Patatine piccanti', 'Patatine piccanti'),
('Croccantelle gusto pizza', 'Croccantelle gusto pizza'),
('Croccantelle gusto bacon', 'Croccantelle gusto bacon'),
('Schiacciatine al rosmarino', 'Schiacciatine al rosmarino'),
('Schiacciatine alle olive', 'Schiacciatine alle olive'),
('Pringles classiche', 'Pringles classiche'),
('Pringles piccanti', 'Pringles piccanti'),
('Cingomme AIR', 'Cingomme AIR'),
('Cingomme Vivident', 'Cingomme Vivident'),
('Big Babol', 'Big Babol'),
('Chupachups assortiti', 'Chupachups assortiti');

INSERT INTO fornitore (CodiceID, nomeTitolare, nomeAzienda, email, passw) VALUES
(100, 'Giovanni Deiana', 'Forno San Marco','info.consegne@fornosanmarco.it', '$2y$10$adKbtpU1/x1pni/kAJVwQ.oLVAsHlsYLFwSrrY5K3DPvfsqtJxs4m'),
(101, 'Antonella Piras', 'Salumi&Sapori','salumi.sapori@gmail.com', '$2y$10$C8nMVVxifOomnXcDRJT17e39pSW8kOnKi5UbP8QaMIO2RVupi.yBm'),
(102, 'Raffaella Conti', 'La Delizia S.r.l.','info@ladelizia.it', '$2y$10$AaDTVkjxUEwWLv1KXQViC.7t/A3q1639x7ea4ttTlYt/3k4aheI2u'),
(103, 'Domenico Ferri', 'SnackItalia S.r.l.','info_consegne@snackitalia.it', '$2y$10$pyhT8I3C3IXE7HYnc9i7/.d9HwZfiAT/OU/yZz..By1Px1lX/4MmS'),
(104, 'Mariangela Lai', 'Brio Distribuzioni','consegne.bevande@briodistribuzioni.it', '$2y$10$vAs02qjG0clzUM0J7aiXzeuzL3bdB3Td0k18FZgsp2SHmbCvIUPM.');

#----------------------------------------
# Qui ci vanno gli insert di Rifornimento
#----------------------------------------

INSERT INTO rifornimento (CodiceID, ingrediente, quantità, data, ora, consegnato, OperatoreID, FornitoreID) VALUES
(1000, 'Acqua naturale', 1500, '2025-05-12','8:32:25', TRUE, 1, 104),
(1001, 'Coca cola', 1000, '2025-05-12','8:32:25', TRUE, 1, 104),
(1002, 'Pane per panini', 800, '2025-05-12', '8:42:51', TRUE, 3, 100),
(1003, 'Schiacciatine al rosmarino', 500, '2025-05-15','10:12:57', FALSE, 4, 103),
(1004, 'Muffin al cioccolato', 500, '2025-05-15','10:12:57', FALSE, 4, 102),
(1005, 'Salame', 100, '2025-05-15','10:12:57', FALSE, 4, 101);

# Impedisce la modifica ai clienti tipo="Studente" sul luogo di consegna
DELIMITER $$
CREATE TRIGGER check_luogoConsegna_modifica
BEFORE UPDATE ON cliente
FOR EACH ROW
BEGIN
    IF OLD.tipoCliente = 'Studente' AND NEW.luogoConsegna <> OLD.luogoConsegna THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gli studenti non possono modificare il luogo di consegna.';
    END IF;
END$$
DELIMITER ;


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
        IF (v_ora_corrente < '08:00:00' OR v_ora_corrente > '22:00:00') THEN
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
                WHERE o.ruolo = 'Addetto-Consegne'
                GROUP BY o.CodiceID
            ) AS conteggi;

            -- Prendi il CodiceID più piccolo tra quelli che hanno conteggio = v_min_assegnamenti
            SELECT MIN(o2.CodiceID) INTO v_operatore_id
            FROM operatore o2
            WHERE o2.ruolo = 'Addetto-Consegne'
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

-- procedura per cambiare la pssw cliente 
DELIMITER $$
CREATE PROCEDURE cambio_pssw_cliente(
    IN p_email VARCHAR(100),
    IN p_n_pssw VARCHAR (255),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
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

        UPDATE cliente
        SET passw = p_n_pssw
        WHERE email = p_email;

        SET p_messaggio = 'Password aggiornata con successo!';

    END this_procedure;
END $$
DELIMITER ;

- ***** PROCEDURE SUI DATI DEGLI OPERATORI *****

-- procedura per inserire un operatore
DELIMITER $$
CREATE PROCEDURE insert_operatore(
    IN p_nome VARCHAR(50),
    IN p_cognome VARCHAR(50),
    IN p_ruolo VARCHAR(30),
    IN p_email VARCHAR(100),
    IN p_default_pssw VARCHAR(255),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_ruolo VARCHAR(30);
    DECLARE v_nome VARCHAR(50);
    DECLARE v_cognome VARCHAR(50);
    DECLARE v_id INT;

    this_procedure: BEGIN
        -- normailizzazione ruolo
        CASE LOWER(p_ruolo)
            WHEN 'titolare' THEN SET v_ruolo = 'Titolare';
            WHEN 'addetto-vendite' THEN SET v_ruolo = 'Addetto-Vendite';
            WHEN 'addetto-consegne' THEN SET v_ruolo = 'Addetto-Consegne';
            WHEN 'addetto vendite' THEN SET v_ruolo = 'Addetto-Vendite';
            WHEN 'addetto consegne' THEN SET v_ruolo = 'Addetto-Consegne';
        ELSE
            SET p_messaggio = CONCAT('Ruolo operatore non valido: ', p_ruolo);
            LEAVE this_procedure;
        END CASE;

        -- Normalizzazione nome e cognome
        SET v_nome = CONCAT( UPPER(LEFT(p_nome, 1)), LOWER(SUBSTRING(p_nome, 2)));
        SET v_cognome = CONCAT( UPPER(LEFT(p_cognome, 1)), LOWER(SUBSTRING(p_cognome, 2)));

        -- Inserimento cliente
        INSERT INTO operatore (nome, cognome, ruolo, email, passw)
        VALUES (v_nome, v_cognome, v_ruolo, LOWER(p_email), p_default_pssw);

        SELECT CodiceID into v_id
        FROM operatore
        WHERE email = LOWER(p_email);

        SET p_messaggio = CONCAT('Inserito nuovo operatore: ',v_nome,' ', v_cognome, ' ',LOWER(p_email), ' ',v_ruolo, ' con ID: ', v_id );

    END this_procedure;
END $$
DELIMITER ;

-- procedura per la modifica dei dati di un operatore
DELIMITER $$
CREATE PROCEDURE modifica_operatore(
    IN p_id INT,
    IN p_email VARCHAR(100),
    IN p_nome VARCHAR(50),
    IN p_cognome VARCHAR(50),
    IN p_ruolo VARCHAR(30),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_ruolo VARCHAR(30);
    DECLARE v_nome VARCHAR(50);
    DECLARE v_cognome VARCHAR(50);
    DECLARE v_op_esiste BOOLEAN;

    this_procedure: BEGIN
        -- controllo esistenza op
        SELECT COUNT(*) > 0 INTO v_op_esiste
        FROM operatore
        WHERE CodiceID = p_id;

        IF NOT v_op_esiste THEN
            SET p_messaggio = 'Operatore non trovato';
            LEAVE this_procedure;
        END IF;

        -- normailizzazione ruolo
        CASE LOWER(p_ruolo)
            WHEN 'titolare' THEN SET v_ruolo = 'Titolare';
            WHEN 'addetto-vendite' THEN SET v_ruolo = 'Addetto-Vendite';
            WHEN 'addetto-consegne' THEN SET v_ruolo = 'Addetto-Consegne';
            WHEN 'addetto vendite' THEN SET v_ruolo = 'Addetto-Vendite';
            WHEN 'addetto consegne' THEN SET v_ruolo = 'Addetto-Consegne';
        ELSE
            SET p_messaggio = CONCAT('Ruolo operatore non valido: ', p_ruolo);
            LEAVE this_procedure;
        END CASE;

        -- Normalizzazione nome e cognome
        SET v_nome = CONCAT( UPPER(LEFT(p_nome, 1)), LOWER(SUBSTRING(p_nome, 2)));
        SET v_cognome = CONCAT( UPPER(LEFT(p_cognome, 1)), LOWER(SUBSTRING(p_cognome, 2)));

        -- Esegui UPDATE
        UPDATE operatore
        SET nome = v_nome,
            cognome = v_cognome,
            ruolo = v_ruolo,
            email = LOWER(p_email)
        WHERE CodiceID = p_id;

        SET p_messaggio = CONCAT('Operatore con ID:', p_id ,' aggiornato con nuovi dati: ', v_nome,' ', v_cognome,' ', LOWER(p_email),' ', v_ruolo); 

    END this_procedure;

END $$
DELIMITER ;


-- procedura per cambiare la pssw operatore
DELIMITER $$
CREATE PROCEDURE cambio_pssw_operatore(
    IN p_id INT,
    IN p_n_pssw VARCHAR (255),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    -- DECLARE v_v_pssw VARCHAR(255);
    DECLARE v_op_esiste BOOLEAN;

    this_procedure: BEGIN
        -- controllo esistenza op
        SELECT COUNT(*) > 0 INTO v_op_esiste
        FROM operatore
        WHERE CodiceID = p_id;

        IF NOT v_op_esiste THEN
            SET p_messaggio = 'Operatore non trovato';
            LEAVE this_procedure;
        END IF;

        -- controllo che la nuova pssw non sia vuota
        IF LENGTH(TRIM(p_n_pssw)) = 0 THEN
            SET p_messaggio = 'La nuova password non può essere vuota';
            LEAVE this_procedure;
        END IF;

        UPDATE operatore
        SET passw = p_n_pssw
        WHERE CodiceID = p_id;

        SET p_messaggio = 'Password aggiornata con successo!';

    END this_procedure;
END $$
DELIMITER ;


-- procedura per eliminare un operatore
DELIMITER $$
CREATE PROCEDURE elimina_operatore(
    IN p_id INT,
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_op_esiste BOOLEAN;
    DECLARE v_ruolo VARCHAR(30);
    DECLARE v_num_titolari INT; 

    this_procedure: BEGIN
        -- Verifica esistenza operatore
        SELECT COUNT(*) > 0 INTO v_op_esiste
        FROM operatore
        WHERE CodiceID = p_id;

        IF NOT v_op_esiste THEN
            SET p_messaggio = 'Operatore non trovato';
            LEAVE this_procedure;
        END IF;

        /* Controllo se l'oeratore che si vuole eliminare è un titolare
        in caso affermativo lo cancello solo se ne esiste almeno un altro*/
        SELECT ruolo INTO v_ruolo
        FROM operatore
        WHERE CodiceID = p_id;

        -- Se è un titolare, controlla quanti ce ne sono
        IF v_ruolo = 'Titolare' THEN
            SELECT COUNT(*) INTO v_num_titolari
            FROM operatore
            WHERE ruolo = 'Titolare';

            IF v_num_titolari = 1 THEN
                SET p_messaggio = 'Non è possibile eliminare l\'unico titolare presente';
                LEAVE this_procedure;
            END IF;
        END IF;

        DELETE FROM operatore
        WHERE CodiceID = p_id;

        SET p_messaggio = CONCAT("Operatore con codice ID ", p_id, " eliminato dal database");

    END this_procedure;
END $$
DELIMITER ;


-- ***** PROCEDURE SUI DATI DEI FORNITORI *****

-- procedura per inserire un fornitore
DELIMITER $$
CREATE PROCEDURE insert_fornitore(
    IN p_nome_titolare VARCHAR(50),
    IN p_nome_azienda VARCHAR(50),
    IN p_email VARCHAR(100),
    IN p_default_pssw VARCHAR(255),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_id INT;

    this_procedure: BEGIN
        -- Inserimento fornitore
        INSERT INTO fornitore (nomeTitolare, nomeAzienda, email, passw)
        VALUES (p_nome_titolare, p_nome_azienda, LOWER(p_email), p_default_pssw);

        SELECT CodiceID into v_id
        FROM fornitore
        WHERE email = LOWER(p_email);

        SET p_messaggio = CONCAT('Inserito nuovo fornitore: ',p_nome_titolare,' ', p_nome_azienda, ' ',LOWER(p_email), ' con ID: ', v_id);

    END this_procedure;
END $$
DELIMITER ;

-- procedura per modificare un fornitore
DELIMITER $$
CREATE PROCEDURE modifica_fornitore(
    IN p_id INT,
    IN p_nome_titolare VARCHAR(50),
    IN p_nome_azienda VARCHAR(50),
    IN p_email VARCHAR(100),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_fornitore_esiste BOOLEAN;

    this_procedure: BEGIN

        -- Verifica esistenza fornitore
        SELECT COUNT(*) > 0 INTO v_fornitore_esiste
        FROM fornitore
        WHERE CodiceID = p_id;

        IF NOT v_fornitore_esiste THEN
            SET p_messaggio = 'Fornitore non trovato';
            LEAVE this_procedure;
        END IF;

        -- Esegui UPDATE
        UPDATE fornitore
        SET nomeTitolare = p_nome_titolare,
            nomeAzienda = p_nome_azienda,
            email = LOWER(p_email)
        WHERE CodiceID = p_id;

        SET p_messaggio = CONCAT('Fornitore con ID: ', p_id,' aggiornato con nuovi dati: ', p_nome_titolare,' ', p_nome_azienda, ' ',LOWER(p_email));

    END this_procedure;
END $$
DELIMITER ;

-- procedura per cambiare la pssw fornitori 
DELIMITER $$
CREATE PROCEDURE cambio_pssw_fornitore(
    IN p_id INT,
    IN p_n_pssw VARCHAR (255),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_fornitore_esiste BOOLEAN;

    this_procedure: BEGIN
        -- controllo esistenza fornitore
        SELECT COUNT(*) > 0 INTO v_fornitore_esiste
        FROM fornitore
        WHERE CodiceID = p_id;

        IF NOT v_fornitore_esiste THEN
            SET p_messaggio = 'Fornitore non trovato';
            LEAVE this_procedure;
        END IF;

        -- controllo che la nuova pssw non sia vuota
        IF LENGTH(TRIM(p_n_pssw)) = 0 THEN
            SET p_messaggio = 'La nuova password non può essere vuota';
            LEAVE this_procedure;
        END IF;

        UPDATE fornitore
        SET passw = p_n_pssw
        WHERE CodiceID = p_id;

        SET p_messaggio = 'Password aggiornata con successo!';

    END this_procedure;
END $$
DELIMITER ;

-- procedura per eliminare un fornitore
DELIMITER $$
CREATE PROCEDURE elimina_fornitore(
    IN p_id VARCHAR(100),
    OUT p_messaggio VARCHAR(255)
)
BEGIN
    DECLARE v_fornitore_esiste BOOLEAN;

    this_procedure: BEGIN
        -- Verifica esistenza fornitore
        SELECT COUNT(*) > 0 INTO v_fornitore_esiste
        FROM fornitore
        WHERE CodiceID = p_id;

        IF NOT v_fornitore_esiste THEN
            SET p_messaggio = 'Fornitore non trovato';
            LEAVE this_procedure;
        END IF;

        DELETE FROM fornitore
        WHERE CodiceID = p_id;

        SET p_messaggio = CONCAT("Fornitore con codce ID ", p_id, " eliminato dal database");

    END this_procedure;
END $$
DELIMITER ;

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
GRANT EXECUTE ON PROCEDURE owlbreak.insert_operatore TO 'Titolare'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.modifica_operatore TO 'Titolare'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.elimina_operatore TO 'Titolare'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_operatore TO 'Titolare'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.insert_fornitore TO 'Titolare'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.modifica_fornitore TO 'Titolare'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.elimina_fornitore TO 'Titolare'@'localhost';



-- PRIVILEGI ADDETTI VENDITE
GRANT SELECT ON owlbreak.operatore TO 'Addetto-Vendite'@'localhost';
GRANT SELECT ON owlbreak.ordine TO 'Addetto-Vendite'@'localhost';
GRANT SELECT ON owlbreak.prodotto TO 'Addetto-Vendite'@'localhost';
GRANT SELECT ON owlbreak.composizione TO 'Addetto-Vendite'@'localhost';
GRANT SELECT ON owlbreak.ingrediente TO 'Addetto-Vendite'@'localhost';
GRANT SELECT ON owlbreak.rifornimento TO 'Addetto-Vendite'@'localhost';
GRANT SELECT ON owlbreak.fornitore TO 'Addetto-Vendite'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_operatore TO 'Addetto-Vendite'@'localhost';

-- PRIVILEGI ADDETTI CCONSEGNE
GRANT SELECT ON owlbreak.operatore TO 'Addetto-Consegne'@'localhost';
GRANT SELECT ON owlbreak.ordine TO 'Addetto-Consegne'@'localhost';
GRANT SELECT ON owlbreak.consegna TO 'Addetto-Consegne'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_operatore TO 'Addetto-Consegne'@'localhost';

-- PRIVILEGI FORNITORI
GRANT SELECT ON owlbreak.rifornimento TO 'Fornitore'@'localhost';
GRANT EXECUTE ON PROCEDURE owlbreak.cambio_pssw_fornitore TO 'Fornitore'@'localhost';