CREATE TABLE Cliente (
    email VARCHAR(100) PRIMARY KEY,
    password VARCHAR(16) NOT NULL,
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

CREATE TABLE Ordine (
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

CREATE TABLE Prodotto (
    nome VARCHAR(50) PRIMARY KEY,
    prezzo DECIMAL(6,2) NOT NULL,
    disponibilità BOOLEAN NOT NULL
);

CREATE TABLE Composizione (
    nomeProdotto VARCHAR(50),
    nomeIngrediente VARCHAR(50),
    PRIMARY KEY (nomeProdotto, nomeIngrediente),
    FOREIGN KEY (nomeProdotto) REFERENCES Prodotto(nome),
    FOREIGN KEY (nomeIngrediente) REFERENCES Ingrediente(nome)
);

CREATE TABLE Ingrediente (
    nome VARCHAR(50) PRIMARY KEY,
    allergeni VARCHAR(200),
    quantità INT NOT NULL CHECK (quantità >= 0)
);

CREATE TABLE Operatore (
    CodiceID INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(16) NOT NULL,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    ruolo VARCHAR(30) NOT NULL
);

CREATE TABLE Rifornimento (
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

CREATE TABLE Fornitore (
    CodiceID INT PRIMARY KEY AUTO_INCREMENT,
    nomeTitolare VARCHAR(50),
    nomeAzienda VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(16)
);