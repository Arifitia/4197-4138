==============================
Dahmyan - Base de données & Logique métier
==============================
[TERMINÉ]

1. Base de données
- [x] Création de la base SQLite
- [x] Création des tables
- [x] Création des relations entre les tables
- [x] Mise à jour du fichier base.sql

2. Gestion des préfixes opérateur
- [x] Ajouter un préfixe opérateur (033, 037...)
- [x] Modifier un préfixe
- [x] Supprimer un préfixe
- [x] Afficher la liste des préfixes

3. Gestion des barèmes de frais
- [x] Ajouter une tranche de montant
- [x] Modifier une tranche
- [x] Supprimer une tranche
- [x] Afficher les barèmes de frais

4. Gestion des opérations financières
- [x] Implémenter le calcul automatique des frais selon le montant
- Gestion du dépôt :
  - [x] Ajouter le montant au solde
  - [x] Enregistrer l'opération

- Gestion du retrait :
  - [x] Vérifier le solde disponible
  - [x] Calculer les frais
  - [x] Déduire le montant + frais
  - [x] Enregistrer l'opération

- Gestion du transfert :
  - [x] Vérifier le destinataire
  - [x] Vérifier le solde de l'expéditeur
  - [x] Calculer les frais
  - [x] Débiter l'expéditeur
  - [x] Créditer le destinataire
  - [x] Enregistrer l'opération


==============================
Arifitia - Authentification, Interface & Consultation
==============================
[TERMINÉ]

1. Authentification client
- [x] Créer la page de connexion par numéro de téléphone
- [x] Vérifier la validité du préfixe opérateur
- [x] Rechercher un client existant
- [x] Créer automatiquement un client si le numéro n'existe pas
- [x] Gérer la session utilisateur
- [x] Ajouter la déconnexion

2. Interface utilisateur
- [x] Installer et configurer Bootstrap
- [x] Créer le layout général
- [x] Créer la navbar
- [x] Créer les menus client et opérateur
- [x] Ajouter les messages de validation et d'erreur
- [x] Adapter l'interface aux écrans mobiles

3. Fonctionnalités client
- [x] Créer le tableau de bord client
- [x] Afficher les informations du client connecté
- [x] Afficher le solde du client
- [x] Afficher l'historique des opérations :
  - [x] Date
  - [x] Type d'opération
  - [x] Montant
  - [x] Frais
  - [x] Destinataire en cas de transfert

4. Gestion opérateur
- CRUD des types d'opérations :
  - [x] Ajouter
  - [x] Modifier
  - [x] Supprimer
  - [x] Afficher

- Situation des comptes clients :
  - [x] Afficher la liste des clients
  - [x] Afficher les numéros
  - [x] Afficher les soldes

- Situation des gains :
  - [x] Calculer les gains des retraits
  - [x] Calculer les gains des transferts
  - [x] Afficher le total des gains


==============================
TRAVAIL COMMUN
==============================

- [x] Correction des erreurs
- [x] Mise à jour du fichier Taches.md
- [x] Préparation de la livraison Git
- [x] Création du tag v1
