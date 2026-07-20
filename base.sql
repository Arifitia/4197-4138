-- ============================================================
-- Version 1
-- ============================================================
-- Table : prefixes
-- ------------------------------------------------------------
DROP TABLE IF EXISTS prefixes;
CREATE TABLE prefixes (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe        TEXT NOT NULL UNIQUE,
    operateur_code TEXT NOT NULL,
    type           TEXT NOT NULL
);

-- ------------------------------------------------------------
-- Table : types_operations (dépôt, retrait, transfert)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS types_operations;
CREATE TABLE types_operations (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    nom  TEXT NOT NULL UNIQUE
);

-- ------------------------------------------------------------
-- Table : baremes
-- ------------------------------------------------------------
DROP TABLE IF EXISTS baremes;
CREATE TABLE baremes (
    id                 INTEGER PRIMARY KEY AUTOINCREMENT,
    type_operation_id  INTEGER NOT NULL,
    montant_min        INTEGER NOT NULL,
    montant_max        INTEGER NOT NULL,
    frais              INTEGER NOT NULL,
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id)
);

-- ------------------------------------------------------------
-- Table : clients (login automatique par numéro, pas d'inscription)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS clients;
CREATE TABLE clients (
    id                INTEGER PRIMARY KEY AUTOINCREMENT,
    numero_telephone  TEXT NOT NULL UNIQUE,
    solde             INTEGER NOT NULL DEFAULT 0,
    date_creation     TEXT NOT NULL DEFAULT (datetime('now'))
);

-- ------------------------------------------------------------
-- Table : transactions
-- Historique des opérations (dépôt, retrait, transfert)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS transactions;
CREATE TABLE transactions (
    id                          INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id                   INTEGER NOT NULL,
    type_operation_id           INTEGER NOT NULL,
    montant                     INTEGER NOT NULL,
    frais                       INTEGER NOT NULL DEFAULT 0,
    client_destinataire_id      INTEGER,
    destinataire_externe_numero TEXT,
    destinataire_externe_code   TEXT,
    withdraw_fee_paid           INTEGER NOT NULL DEFAULT 0,
    bulk_transfer_id            INTEGER,
    date_transaction            TEXT NOT NULL DEFAULT (datetime('now')),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id),
    FOREIGN KEY (client_destinataire_id) REFERENCES clients(id)
);

-- ------------------------------------------------------------
-- Table : mis a jour des soldes
-- ------------------------------------------------------------
DROP TABLE IF EXISTS maj_solde;
CREATE TABLE maj_solde (
    id             INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_id INTEGER NOT NULL,
    client_id      INTEGER NOT NULL,
    solde_avant    INTEGER NOT NULL,
    solde_apres    INTEGER NOT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

-- ------------------------------------------------------------
-- Vues
-- ------------------------------------------------------------

DROP VIEW IF EXISTS vue_situation_clients;
CREATE VIEW vue_situation_clients AS
SELECT id, numero_telephone, solde, date_creation
FROM clients;

DROP VIEW IF EXISTS vue_situation_gains;
CREATE VIEW vue_situation_gains AS
SELECT
    t.nom AS type_operation,
    COUNT(tr.id) AS nombre_operations,
    SUM(tr.frais) AS total_frais
FROM transactions tr
JOIN types_operations t ON t.id = tr.type_operation_id
WHERE t.nom IN ('retrait', 'transfert')
GROUP BY t.nom;

DROP VIEW IF EXISTS vue_transferts_externes;
CREATE VIEW vue_transferts_externes AS
SELECT
    tr.destinataire_externe_code AS operateur_externe,
    COUNT(tr.id) AS nombre_operations,
    SUM(tr.montant) AS total_montant,
    SUM(tr.frais) AS total_frais
FROM transactions tr
WHERE tr.destinataire_externe_code IS NOT NULL
GROUP BY tr.destinataire_externe_code;

DROP VIEW IF EXISTS vue_gains_separes;
CREATE VIEW vue_gains_separes AS
SELECT
    CASE
        WHEN t.nom = 'retrait' THEN 'retrait'
        WHEN t.nom = 'transfert' AND tr.destinataire_externe_code IS NULL THEN 'transfert_interne'
        WHEN t.nom = 'transfert' AND tr.destinataire_externe_code IS NOT NULL THEN 'transfert_externe'
    END AS categorie,
    COUNT(tr.id) AS nombre_operations,
    SUM(tr.frais) AS total_frais
FROM transactions tr
JOIN types_operations t ON t.id = tr.type_operation_id
WHERE t.nom IN ('retrait', 'transfert')
GROUP BY categorie;

-- ------------------------------------------------------------
-- Table : configuration
-- ------------------------------------------------------------
DROP TABLE IF EXISTS configuration;
CREATE TABLE configuration (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    cle    TEXT NOT NULL UNIQUE,
    valeur TEXT NOT NULL
);

INSERT INTO configuration (cle, valeur) VALUES ('commission_externe', '20');

-- ------------------------------------------------------------
-- Table : configuration
-- ------------------------------------------------------------
DROP TABLE IF EXISTS configuration;
CREATE TABLE configuration (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    cle    TEXT NOT NULL UNIQUE,
    valeur TEXT NOT NULL
);

INSERT INTO configuration (cle, valeur) VALUES ('commission_externe', '20');

-- ------------------------------------------------------------
-- Données de départ
-- ------------------------------------------------------------

INSERT INTO prefixes (prefixe, operateur_code, type) VALUES
('034', 'MVOLA', 'interne'),
('038', 'MVOLA', 'interne'),
('033', 'AIRTEL', 'externe'),
('035', 'AIRTEL', 'externe'),
('032', 'ORANGE', 'externe'),
('037', 'ORANGE', 'externe');

INSERT INTO types_operations (nom) VALUES ('depot'), ('retrait'), ('transfert');

-- Barème pour les retraits
INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
((SELECT id FROM types_operations WHERE nom = 'retrait'), 100,      1000,    50),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 1001,     5000,    50),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 5001,     10000,   100),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 10001,    25000,   200),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 25001,    50000,   400),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 50001,   100000,   800),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 100001,  250000,  1500),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 250001,  500000,  1500),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 500001, 1000000,  2500),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 1000001, 2000000, 3000);

-- Barème pour les transferts
INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
((SELECT id FROM types_operations WHERE nom = 'transfert'), 100,      1000,    30),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 1001,     5000,    30),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 5001,     10000,   60),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 10001,    25000,   120),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 25001,    50000,   250),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 50001,   100000,   500),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 100001,  250000,   900),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 250001,  500000,   900),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 500001, 1000000,  1500),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 1000001, 2000000, 1800);

-- Quelques clients de test MVola
INSERT INTO clients (numero_telephone, solde) VALUES
('0341234567', 50000),
('0382345678', 15000);
