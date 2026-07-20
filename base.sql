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
-- (voir section détaillée ci-dessous)

-- ------------------------------------------------------------
-- Clients de test MVola
-- ------------------------------------------------------------

INSERT INTO clients (numero_telephone, solde) VALUES
('0341234567', 50000),
('0342345678', 120000),
('0343456789', 35000),
('0344567890', 980000),
('0345678901', 12500),
('0346789012', 76000),
('0347890123', 410000),
('0348901234', 1500000),
('0349012345', 230000),
('0341122334', 89000),
('0342233445', 560000),
('0343344556', 71000),
('0344455667', 26000),
('0345566778', 900000),
('0346677889', 175000),
('0347788990', 42000),
('0348899001', 650000),
('0349900112', 305000),

('0381234567', 15000),
('0382345678', 95000),
('0383456789', 60000),
('0384567890', 270000),
('0385678901', 730000),
('0386789012', 22000),
('0387890123', 180000),
('0388901234', 98000),
('0389012345', 510000),
('0381122334', 420000),
('0382233445', 68000),
('0383344556', 760000),
('0384455667', 91000),
('0385566778', 290000),
('0386677889', 37000),
('0387788990', 1100000),
('0388899001', 820000),
('0389900112', 47000);

-- Dépôts
INSERT INTO transactions (client_id, type_operation_id, montant, frais)
VALUES
(1, 1, 100000, 0),
(2, 1, 50000, 0),
(3, 1, 250000, 0),
(4, 1, 1000000, 0),
(5, 1, 80000, 0);

-- Retraits
INSERT INTO transactions (client_id, type_operation_id, montant, frais)
VALUES
(1, 2, 10000, 100),
(2, 2, 5000, 50),
(3, 2, 70000, 800),
(6, 2, 20000, 200),
(8, 2, 150000, 1500);

-- Transferts internes
INSERT INTO transactions
(client_id, type_operation_id, montant, frais, client_destinataire_id)
VALUES
(1,3,15000,120,2),
(2,3,30000,250,5),
(6,3,250000,900,10),
(8,3,50000,500,4),
(12,3,90000,500,15);

-- Transferts externes Airtel
INSERT INTO transactions
(client_id,type_operation_id,montant,frais,destinataire_externe_numero,destinataire_externe_code)
VALUES
(3,3,40000,300,'0331234567','AIRTEL'),
(5,3,90000,600,'0359876543','AIRTEL'),
(9,3,120000,1080,'0334567890','AIRTEL');

-- Transferts externes Orange
INSERT INTO transactions
(client_id,type_operation_id,montant,frais,destinataire_externe_numero,destinataire_externe_code)
VALUES
(4,3,60000,360,'0324567890','ORANGE'),
(7,3,200000,1800,'0371234567','ORANGE'),
(10,3,45000,300,'0327654321','ORANGE');

INSERT INTO maj_solde (transaction_id, client_id, solde_avant, solde_apres)
VALUES
(1,1,50000,150000),
(2,2,120000,170000),
(3,3,35000,285000),
(4,4,980000,1980000),
(5,5,12500,92500),
(6,1,150000,139900),
(7,2,170000,164950),
(8,3,285000,214200),
(9,6,76000,55800),
(10,8,1500000,1348500);