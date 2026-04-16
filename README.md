🚗 EcoRide

Application web de covoiturage écologique développée en PHP orienté objet avec une architecture MVC.

---

 📌 Présentation du projet

EcoRide est une plateforme de covoiturage permettant :

- 🔍 La recherche de trajets
- 🚗 La proposition de trajets par des conducteurs
- 🎟️ La réservation de places
- ⭐ La gestion des avis
- 💳 La gestion des crédits
- 👨‍💼 Un espace employé pour valider les avis
- 🛠️ Un espace administrateur pour gérer les comptes

Projet réalisé dans le cadre d’un **ECF Développeur Web**.

---

 ⚠️ Version du projet

👉 La **version finale corrigée** du projet se trouve dans le dossier :


ecoride/


Les autres fichiers à la racine correspondent à une ancienne structure conservée à titre d’historique.

---

 🛠️ Technologies utilisées

- PHP 8+
- MySQL
- Architecture MVC
- HTML5
- CSS3 (responsive)
- JavaScript (interactions admin)
- XAMPP (environnement local)
- phpMyAdmin

---

 📂 Structure du projet


ecoride/
├── app/
│ ├── Controllers/
│ ├── Models/
│ ├── Views/
│ ├── Core/
├── public/
│ ├── index.php
│ ├── assets/
├── docs/
├── includes/
├── pages/


---

 ⚙️ Installation du projet

 1️⃣ Cloner le projet

```bash
git clone https://github.com/Nathan79300/ecoridestudi.git
2️⃣ Base de données
Ouvrir phpMyAdmin
Créer une base de données :
ecoride
Importer le fichier :
docs/ecoride_structure_et_donnees.sql
3️⃣ Configuration base de données

Dans le fichier :

app/Core/Database.php

Configurer :

host: localhost
port: 3307
dbname: ecoride
username: root
password: (vide par défaut sous XAMPP)

⚠️ Selon votre configuration locale, le port peut être 3306.

4️⃣ Lancer le projet

Placer le dossier dans :

C:\xampp\htdocs\ecoridestudi

Puis accéder à :

👉 URL principale :

http://localhost/ecoridestudi/ecoride/public/
👤 Comptes de test
🧑 Utilisateur
Email : utilisateur@example.com
Mot de passe : utilisateur123
👨‍💼 Employé
Email : employe@ecoride.fr
Mot de passe : employe123
👨‍💻 Administrateur
Email : admin@ecoride.fr
Mot de passe : admin123
📋 Fonctionnalités principales
👤 Utilisateur
Inscription / Connexion
Recherche de trajets
Réservation
Gestion du profil
Dépôt d’avis
🚗 Chauffeur
Proposer un trajet
Gérer ses trajets
Démarrer / Clôturer un trajet
👨‍💼 Employé
Validation des avis
Consultation des signalements
🛠️ Administrateur
Gestion des comptes
Suspension / réactivation des utilisateurs
Suivi global
🎨 Charte graphique
Vert principal : #2ecc71
Vert foncé : #27ae60
Vert clair : #a7f0c4
Texte principal : #333333
Texte secondaire : #666666
Blanc : #ffffff

Police utilisée : Poppins (Google Fonts)

📱 Responsive

L’application est entièrement responsive et adaptée aux mobiles.

📄 Documents fournis

Disponibles dans le dossier /docs :

Manuel d’utilisation
Charte graphique
Diagramme MCD
Diagrammes UML
Kanban
Script SQL