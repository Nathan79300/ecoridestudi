# EcoRide - Application de Covoiturage 🚗🌿

## 📁 Déploiement local

### ✅ Prérequis

* PHP >= 8.4
* MySQL >= 5.7 ou MariaDB
* Serveur local : XAMPP / WAMP / Laragon / MAMP
* Navigateur moderne (Chrome, Firefox, etc.)

---

### <<< 1. Cloner le projet

```bash
git clone https://github.com/Nathan79300/ecoride.git
cd ecoride
```

### <<< 2. Configuration de la base de données

* Créer une base de données nommée `ecoride`
* Importer le fichier `ecoride_structure_donnees.sql` dans PhpMyAdmin ou via la ligne de commande :

```sql
source chemin/vers/ecoride_structure_donnees.sql;
```

### <<< 3. Configuration du projet

* Créer un fichier `includes/db.php` avec vos identifiants :

```php
<?php
$pdo = new PDO("mysql:host=localhost;dbname=ecoride;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

### <<< 4. Lancer le projet

* Placer le dossier `ecoride` dans `htdocs` (XAMPP) ou le dossier web root
* Accéder à l'application via :

```
http://localhost/ecoride/index.php
```

---

## 🌐 Structure Git

* `main` : branche principale stable
* `develop` : branche de développement
* `feature/nom-fonctionnalité` : branches fonctionnelles

### Workflow Git

1. Création d'une branche `feature/` à partir de `develop`
2. Tests locaux, puis merge vers `develop`
3. Tests globaux, puis merge vers `main`

---

## 👥 Comptes de test

### 📅 Utilisateur simple

* Email : `utilisateur@exemple.com`
* Mot de passe : `Util123*`

### 🚗 Chauffeur

* Email : `alice@example.com`
* Mot de passe : `alice123`

### 👷️ Employé

* Email : `paul@ecoride.fr`
* Mot de passe : `paul123`
* URL directe : [`http://localhost/ecoride/index.php?page=connexion_employe`](http://localhost/ecoride/index.php?page=connexion_employe)

### 🛠️ Administrateur

* Email : `admin@ecoride.fr`
* Mot de passe : `admin123`
* URL directe : [`http://localhost/ecoride/index.php?page=connexion_admin`](http://localhost/ecoride/index.php?page=connexion_admin)

---

## 📧 Contact

* Email : `contact@ecoride.fr`

---


