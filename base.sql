-- ============================================================
-- Version 1
-- ============================================================
-- Table : prefixes
-- ------------------------------------------------------------
CREATE TABLE prefixes (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe  TEXT NOT NULL UNIQUE
);

-- ------------------------------------------------------------
-- Table : types_operations (dépôt, retrait, transfert)
-- ------------------------------------------------------------
CREATE TABLE types_operations (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    nom  TEXT NOT NULL UNIQUE
);

-- ------------------------------------------------------------
-- Table : baremes
-- ------------------------------------------------------------
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
CREATE TABLE transactions (
    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id               INTEGER NOT NULL,
    type_operation_id       INTEGER NOT NULL,
    montant                 INTEGER NOT NULL,
    frais                   INTEGER NOT NULL DEFAULT 0,
    client_destinataire_id  INTEGER,
    date_transaction        TEXT NOT NULL DEFAULT (datetime('now')),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (type_operation_id) REFERENCES types_operations(id),
    FOREIGN KEY (client_destinataire_id) REFERENCES clients(id)
);
-- ------------------------------------------------------------
-- Table : mis a jour des soldes
-- ------------------------------------------------------------
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

-- Situation des comptes clients
CREATE VIEW vue_situation_clients AS
SELECT id, numero_telephone, solde, date_creation
FROM clients;

-- Situation des gains de l'opérateur (frais collectés sur retrait et transfert)
CREATE VIEW vue_situation_gains AS
SELECT
    t.nom AS type_operation,
    COUNT(tr.id) AS nombre_operations,
    SUM(tr.frais) AS total_frais
FROM transactions tr
JOIN types_operations t ON t.id = tr.type_operation_id
WHERE t.nom IN ('retrait', 'transfert')
GROUP BY t.nom;

-- ------------------------------------------------------------
-- Données de départ
-- ------------------------------------------------------------

INSERT INTO prefixes (prefixe) VALUES ('033'), ('037'), ('032'), ('034'), ('038'), ('035');

INSERT INTO types_operations (nom) VALUES ('depot'), ('retrait'), ('transfert');

-- Barème pour les retraits
INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
((SELECT id FROM types_operations WHERE nom = 'retrait'), 100,      1000,    50),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 1001,     5000,    50),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 5001,     10000,   100),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 10001,    25000,   200),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 25001,    50000,   400),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 50001,    100000,  800),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 100001,   250000,  1500),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 250001,   500000,  1500),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 500001,   1000000, 2500),
((SELECT id FROM types_operations WHERE nom = 'retrait'), 1000001,  2000000, 3000);

-- Barème pour les transferts
INSERT INTO baremes (type_operation_id, montant_min, montant_max, frais) VALUES
((SELECT id FROM types_operations WHERE nom = 'transfert'), 100,      1000,    30),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 1001,     5000,    30),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 5001,     10000,   60),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 10001,    25000,   120),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 25001,    50000,   250),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 50001,    100000,  500),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 100001,   250000,  900),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 250001,   500000,  900),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 500001,   1000000, 1500),
((SELECT id FROM types_operations WHERE nom = 'transfert'), 1000001,  2000000, 1800);

-- Le dépôt est en général gratuit : pas de ligne dans baremes,
-- ou on peut ajouter une seule ligne à frais = 0 si vous préférez la gérer pareil.

-- Quelques clients de test
INSERT INTO clients (numero_telephone, solde) VALUES
('0331234567', 50000),
('0372345678', 15000);
