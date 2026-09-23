🚗 EcoRide

Application web de covoiturage écologique développée en PHP orienté objet, selon une architecture MVC.

EcoRide permet aux utilisateurs de rechercher et proposer des trajets, de participer à des covoiturages, de gérer des crédits et de déposer des avis. L'application intègre également des espaces dédiés aux employés et aux administrateurs.

Projet réalisé dans le cadre de l'ECF Développeur Web / DWWM.

📌 Présentation du projet

EcoRide propose notamment :

🔍 Recherche de trajets

🚗 Proposition de trajets par les chauffeurs

🎟️ Participation à un trajet

🚘 Gestion des véhicules et des préférences

💳 Système de crédits

⭐ Dépôt et validation des avis

🚨 Gestion des signalements et trajets problématiques

👨‍💼 Espace employé

🛠️ Espace administrateur

📊 Consultation des statistiques

📱 Interface responsive

L'application repose sur une architecture MVC/POO, une base relationnelle MySQL et une base NoSQL MongoDB Atlas utilisée pour la journalisation des activités.

📂 Organisation du projet

Le dépôt contient une ancienne structure conservée à titre d'historique ainsi que le projet final.

La version utilisée pour l'application et la remise ECF se trouve dans :

ecoride/

Structure principale du projet final :

ecoride/
├── app/
│   ├── Controllers/
│   ├── Models/
│   └── Core/
│       ├── Database.php
│       ├── Router.php
│       └── ...
├── public/
│   ├── index.php
│   └── assets/
│       ├── css/
│       ├── js/
│       │   └── admin.js
│       └── images/
├── views/
├── docs/
├── composer.json
├── composer.lock
├── Dockerfile
└── ...

Le fichier README.md est situé à la racine du dépôt.

Le dossier docs/ contient les documents nécessaires à la présentation du projet.

🛠️ Technologies utilisées

Application

PHP 8.2.12 dans l'environnement local

Programmation orientée objet (POO)

Architecture MVC

HTML5

CSS3

JavaScript

fetch() pour un traitement asynchrone dans l'espace administrateur

Poppins pour la typographie de l'interface

Bases de données

MySQL pour les données métier

MongoDB Atlas pour la journalisation des activités

PDO pour la connexion à MySQL

mongodb/mongodb pour la connexion PHP à MongoDB

Développement et déploiement

XAMPP pour le développement local

Composer pour la gestion des dépendances PHP

Docker pour la conteneurisation

Git / GitHub pour le versionnement

Render pour l'hébergement de l'application

Aiven pour l'hébergement de la base MySQL de production

MongoDB Atlas pour la base NoSQL

🗄️ Bases de données

EcoRide utilise deux solutions complémentaires.

MySQL

MySQL est utilisé pour les données métier de l'application.

Principales tables :

utilisateurs
vehicules
preferences
trajets
participations
avis

En local :

Base : ecoride
Hôte : localhost
Port : 3307
Utilisateur : root
Mot de passe : vide par défaut sous XAMPP

La connexion est gérée par :

app/Core/Database.php

MongoDB Atlas

MongoDB Atlas est utilisé comme base NoSQL complémentaire pour la journalisation des activités de l'application.

La connexion est réalisée en PHP avec la bibliothèque :

mongodb/mongodb

La chaîne de connexion MongoDB est stockée dans une variable d'environnement et n'est pas inscrite en clair dans le code source.

📦 Installation du projet en local

1. Cloner le dépôt

git clone https://github.com/Nathan79300/ecoridestudi.git

Puis se placer dans le projet :

cd ecoridestudi/ecoride

2. Installer les dépendances

Composer doit être installé sur la machine.

Depuis le dossier ecoride/ :

composer install

Les dépendances sont définies dans :

composer.json

et verrouillées dans :

composer.lock

3. Préparer MySQL

Ouvrir XAMPP et démarrer :

Apache

MySQL

Vérifier que MySQL utilise le port :

3307

Créer une base de données nommée :

ecoride

Importer ensuite le fichier SQL présent dans :

ecoride/docs/ecoride_structure_et_donnees.sql

4. Configuration locale

La connexion MySQL est gérée par :

ecoride/app/Core/Database.php

Pour l'environnement local, les paramètres utilisés sont :

Hôte : localhost
Port : 3307
Base : ecoride
Utilisateur : root
Mot de passe : vide par défaut sous XAMPP

La configuration de production utilise les paramètres d'environnement et les services distants prévus pour le déploiement.

5. Lancer l'application

Placer le dépôt dans le répertoire htdocs de XAMPP.

Exemple :

C:\xampp\htdocs\ecoridestudi

Puis accéder à :

http://localhost:8080/ecoridestudi/ecoride/public/

👤 Comptes de démonstration

🧑 Utilisateur

Email : utilisateur@example.com
Mot de passe : utilisateur123

Fonctionnalités principales :

Rechercher un trajet

Participer à un trajet

Annuler une participation

Gérer son profil

Consulter ses crédits

Déposer un avis

👨‍💼 Employé

Email : employe@ecoride.fr
Mot de passe : employe123

Fonctionnalités principales :

Valider ou refuser les avis

Consulter les signalements

Gérer les trajets problématiques

👨‍💻 Administrateur

Email : admin@ecoride.fr
Mot de passe : admin123

Fonctionnalités principales :

Gérer les comptes

Suspendre ou réactiver des utilisateurs

Gérer les comptes employés

Consulter les statistiques

Gérer la plateforme

Ces comptes sont des comptes de démonstration destinés à la présentation du projet.

📋 Fonctionnalités principales

👤 Utilisateur

Inscription

Connexion

Recherche de trajets

Consultation des détails d'un trajet

Participation à un trajet

Annulation d'une participation

Gestion du profil

Gestion des crédits

Dépôt d'avis

🚗 Chauffeur

Déclarer un véhicule

Définir ses préférences

Proposer un trajet

Gérer ses trajets

Démarrer un trajet

Clôturer un trajet à l'arrivée

Participer également à des trajets

👨‍💼 Employé

Consulter les avis

Valider ou refuser les avis

Consulter les signalements

Traiter les trajets problématiques

🛠️ Administrateur

Gérer les utilisateurs

Suspendre ou réactiver des comptes

Créer des comptes employés

Consulter les statistiques

Suivre l'activité globale de la plateforme

💳 Système de crédits

EcoRide utilise un système de crédits pour gérer les opérations liées aux trajets.

Le fonctionnement comprend notamment :

Attribution de crédits au compte utilisateur

Débit lors d'une participation selon les règles métier

Coût associé à la proposition d'un trajet

Traitement des crédits du chauffeur après la validation prévue

Remboursement prévu en cas d'annulation

Possibilité de bloquer le traitement des crédits lorsqu'un problème est signalé

⭐ Avis et signalements

Après un trajet, le passager peut déposer un avis.

Le processus comprend :

Dépôt de l'avis

Enregistrement de l'avis

Mise en attente de validation

Contrôle par un employé

Validation ou refus

Affichage de l'avis lorsqu'il est validé

Un problème peut également être signalé après un trajet. L'employé peut alors intervenir avant la finalisation du traitement concerné.

⚡ JavaScript et traitement asynchrone

Le projet contient du JavaScript côté interface.

Un appel asynchrone avec fetch() est notamment utilisé dans :

public/assets/js/admin.js

L'appel transmet une action au serveur en POST et utilise await fetch(), permettant de traiter l'action sans rechargement complet de la page.

🔐 Sécurité

Le projet met notamment en œuvre :

Hashage des mots de passe

Contrôle des sessions

Vérification des rôles

Protection des routes

Requêtes préparées avec PDO

Validation des données côté serveur

Variables d'environnement pour les informations sensibles de production

Gestion des dépendances avec Composer

Les mots de passe et informations sensibles des bases de production ne sont pas stockés dans le dépôt Git.

🐳 Docker

Le projet est conteneurisé avec Docker.

L'environnement Docker comprend notamment les extensions nécessaires au fonctionnement du projet :

Extension MongoDB PHP

Extension ZIP nécessaire à Composer

Le fichier de configuration principal est :

Dockerfile

Les dépendances PHP sont installées à partir de composer.json et composer.lock.

Le dossier vendor/ n'est pas versionné dans Git.

📱 Responsive Design

L'interface est conçue pour s'adapter aux différents supports :

💻 Ordinateur

📱 Mobile

📲 Tablette

L'adaptation responsive repose notamment sur les Media Queries CSS.

🎨 Charte graphique

Couleurs principales utilisées dans l'interface :

Vert principal : #2ecc71
Vert foncé      : #27ae60
Vert clair      : #a7f0c4
Texte principal : #333333
Texte secondaire : #666666
Blanc           : #ffffff

Police principale :

Poppins

La charte graphique complète est disponible dans le dossier :

ecoride/docs/

🚀 Déploiement

GitHub

Dépôt du projet :

https://github.com/Nathan79300/ecoridestudi

Render

L'application est déployée sur :

https://ecoridestudi.onrender.com

Aiven

La base MySQL utilisée en production est hébergée sur Aiven.

Les informations de connexion de production sont configurées à l'aide de variables d'environnement.

MongoDB Atlas

MongoDB Atlas fournit la base NoSQL utilisée pour la journalisation des activités.

Les informations de connexion sont stockées dans une variable d'environnement.

📄 Documents du projet

Les documents de présentation et de documentation sont disponibles dans :

ecoride/docs/

Ils comprennent notamment :

📘 Manuel d'utilisation

🎨 Charte graphique

🗂️ Diagramme de cas d'utilisation

🔄 Diagramme de séquence

🗃️ MCD

📋 Kanban

🗄️ Script SQL

📑 Dossier de gestion et documentation technique

📊 Architecture générale

                    ┌──────────────────────┐
                    │       EcoRide        │
                    │      PHP MVC/POO     │
                    └──────────┬───────────┘
                               │
              ┌────────────────┴────────────────┐
              │                                 │
       ┌──────▼──────┐                   ┌──────▼──────┐
       │    MySQL    │                   │  MongoDB    │
       │ Données     │                   │   Atlas     │
       │ métier      │                   │ Journalisation│
       └─────────────┘                   └─────────────┘

🌐 Environnements

Développement local

XAMPP
Apache
MySQL
PHP
Composer
MongoDB
Docker

URL :

http://localhost:8080/ecoridestudi/ecoride/public/

Production

GitHub → Render
           │
           ├── Application PHP
           ├── Aiven → MySQL
           └── MongoDB Atlas → NoSQL

👩‍💻 Auteur

Projet réalisé dans le cadre de la formation Développeur Web et Web Mobile (DWWM) et de l'ECF.

Projet : EcoRide