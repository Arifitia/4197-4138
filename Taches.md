(Nisy probleme ilay Git any Arifitia dia tsy afaka ni-push dia nalefany tamny cle ny partie any ao amin'ny v1)

==============================
Dahmyan - Base de données & Logique métier
==============================
[TERMINÉ V1]

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

Gestion du dépôt :
- [x] Ajouter le montant au solde
- [x] Enregistrer l'opération

Gestion du retrait :
- [x] Vérifier le solde disponible
- [x] Calculer les frais
- [x] Déduire le montant + frais
- [x] Enregistrer l'opération

Gestion du transfert :
- [x] Vérifier le destinataire
- [x] Vérifier le solde de l'expéditeur
- [x] Calculer les frais
- [x] Débiter l'expéditeur
- [x] Créditer le destinataire
- [x] Enregistrer l'opération


==============================
Arifitia - Authentification, Interface & Consultation
==============================
[TERMINÉ V1]

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
CRUD des types d'opérations :
- [x] Ajouter
- [x] Modifier
- [x] Supprimer
- [x] Afficher

Situation des comptes clients :
- [x] Afficher la liste des clients
- [x] Afficher les numéros
- [x] Afficher les soldes

Situation des gains :
- [x] Calculer les gains des retraits
- [x] Calculer les gains des transferts
- [x] Afficher le total des gains


==============================
Arifitia - Gestion opérateur MVola (Version 2)
==============================
[EN COURS]

1. Configuration des préfixes des opérateurs externes
- [x] Ajouter la gestion des préfixes des autres opérateurs :
  - [x] Airtel : 033, 035
  - [x] Orange : 032, 037
- [x] Associer chaque préfixe à son opérateur externe
- [x] Détecter automatiquement l'opérateur à partir d'un numéro
- [x] Empêcher les numéros externes d'être considérés comme des clients MVola
- [x] Afficher la liste des préfixes avec :
  - [x] Préfixe
  - [x] Opérateur
  - [x] Type (interne/externe)

2. Configuration des commissions supplémentaires pour les transferts externes
- [x] Ajouter une configuration du pourcentage de commission externe
- [x] Permettre la modification du pourcentage par l'opérateur MVola
- [x] Appliquer la commission supplémentaire lors des transferts vers :
  - [x] Airtel
  - [x] Orange
- [x] Garder le calcul normal des frais pour les transferts MVola → MVola
- [x] Vérifier le calcul des frais selon le type de transfert

3. Séparation de la situation des gains
- [x] Modifier la page "Situation gain via les différents frais"
- [x] Séparer les gains par catégorie :
  - [x] Gains provenant des retraits
  - [x] Gains provenant des transferts MVola → MVola
  - [x] Gains provenant des transferts vers autres opérateurs
- [x] Afficher les totaux séparément
- [x] Mettre à jour les calculs selon les nouvelles règles de transfert

4. Situation des montants à envoyer aux opérateurs externes
- [x] Créer une vue de suivi des montants dus aux opérateurs externes
- [x] Calculer les montants envoyés vers chaque opérateur :
  - [x] Airtel
  - [x] Orange
- [x] Afficher le détail des montants à reverser
- [x] Ajouter une page de consultation opérateur


==============================
Dahmyan - Fonctionnalités client (Version 2)
==============================
[xN COURS]

1. Option inclure les frais de retrait lors de l'envoi
- [x] Ajouter une option lors du transfert permettant d'inclure les frais
- [x] Calculer automatiquement le montant total débité
- [x] Vérifier le solde disponible selon l'option choisie
- [x] Afficher clairement :
  - [x] Montant envoyé
  - [x] Frais appliqués
  - [x] Montant total débité


2. Envoi multiple vers plusieurs numéros MVola
- [x] Ajouter la possibilité d'envoyer vers plusieurs destinataires
- [x] Vérifier que tous les numéros appartiennent au même opérateur MVola
- [x] Diviser automatiquement le montant entre les différents destinataires
- [x] Vérifier le solde avant validation
- [x] Enregistrer chaque transfert dans l'historique
- [x] Afficher le résultat de chaque envoi


==============================
TRAVAIL COMMUN - Version 2
==============================

- [x] Correction des erreurs
- [x] Tests des nouvelles fonctionnalités
- [x] Mise à jour du fichier Taches.md
- [x] Préparation de la livraison Git
- [x] Création du tag v2

==============================================
Alea 1 : Arifitia : changement des pourcentages
==============================================

- [x] Prier
- [x] Ajout de commission externe dans la base (clée étrangère)
- [x] modification manuelle dans la base pour le pourcentage
- [ ] modification depuis le site d'apres input


zao %

new page :
samy afa pour chaque client % (pourcentage d'epargne)
%= 5% epargne = rehefa misy vola tonga de 5% makany amny epargne 


table vaovao
epargne id epargne; id client; pourcentage; date;
client_epargne id client; argent epargne

controller 
modifiaction operationController

view
page pour entrer le pourcentage







