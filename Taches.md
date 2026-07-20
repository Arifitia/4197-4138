==============================
Dahmyan - Base de données & Logique métier
==============================

1. Base de données
- Création de la base SQLite
- Création des tables
- Création des relations entre les tables
- Mise à jour du fichier base.sql

2. Gestion des préfixes opérateur
- Ajouter un préfixe opérateur (033, 037...)
- Modifier un préfixe
- Supprimer un préfixe
- Afficher la liste des préfixes

3. Gestion des barèmes de frais
- Ajouter une tranche de montant
- Modifier une tranche
- Supprimer une tranche
- Afficher les barèmes de frais

4. Gestion des opérations financières
- Implémenter le calcul automatique des frais selon le montant
- Gestion du dépôt :
  - Ajouter le montant au solde
  - Enregistrer l'opération

- Gestion du retrait :
  - Vérifier le solde disponible
  - Calculer les frais
  - Déduire le montant + frais
  - Enregistrer l'opération

- Gestion du transfert :
  - Vérifier le destinataire
  - Vérifier le solde de l'expéditeur
  - Calculer les frais
  - Débiter l'expéditeur
  - Créditer le destinataire
  - Enregistrer l'opération


==============================
Arifitia - Authentification, Interface & Consultation
==============================

1. Authentification client
- Créer la page de connexion par numéro de téléphone
- Vérifier la validité du préfixe opérateur
- Rechercher un client existant
- Créer automatiquement un client si le numéro n'existe pas
- Gérer la session utilisateur
- Ajouter la déconnexion

2. Interface utilisateur
- Installer et configurer Bootstrap
- Créer le layout général
- Créer la navbar
- Créer les menus client et opérateur
- Ajouter les messages de validation et d'erreur
- Adapter l'interface aux écrans mobiles

3. Fonctionnalités client
- Créer le tableau de bord client
- Afficher les informations du client connecté
- Afficher le solde du client
- Afficher l'historique des opérations :
  - Date
  - Type d'opération
  - Montant
  - Frais
  - Destinataire en cas de transfert

4. Gestion opérateur
- CRUD des types d'opérations :
  - Ajouter
  - Modifier
  - Supprimer
  - Afficher

- Situation des comptes clients :
  - Afficher la liste des clients
  - Afficher les numéros
  - Afficher les soldes

- Situation des gains :
  - Calculer les gains des retraits
  - Calculer les gains des transferts
  - Afficher le total des gains


==============================
TRAVAIL COMMUN
==============================

- Intégration des fonctionnalités
- Tests complets du système
- Correction des erreurs
- Mise à jour du fichier Taches.md
- Préparation de la livraison Git
- Création du tag v1