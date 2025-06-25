# recurring – Gestionnaire de tâches récurrentes

![PHP](https://img.shields.io/badge/php-8.1+-8892BF.svg?logo=php)
![Symfony](https://img.shields.io/badge/Symfony-6.x-black.svg?logo=symfony)
![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)

---

**recurring** est une application web légère développée avec Symfony. Elle permet de créer, organiser et suivre des tâches récurrentes, accompagnées de journaux (logs) et de fichiers liés.

---

## ✨ Fonctionnalités

- Création et édition de tâches récurrentes
- Définition de la fréquence et de la date de la prochaine exécution
- Ajout de journaux/commentaires à chaque tâche
- Possibilité d’associer des fichiers
- Interface sobre et claire pour la consultation

---

## ⚙️ Stack technique

- **PHP** 8.x
- **Symfony** 6.x
- **Doctrine ORM**
- **Twig**
- **Composer**
- **PHPUnit**

---

## 🚀 Installation locale

### Prérequis

Assurez-vous d’avoir installé sur votre machine :

- PHP 8.1 ou supérieur
- Composer : https://getcomposer.org/
- Symfony CLI : https://symfony.com/download
- Une base de données compatible (MySQL, PostgreSQL…)
- Git

### Étapes d’installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/bertrand-dwap/recurring.git
cd recurring

# 2. Installer les dépendances PHP
composer install

# 3. Copier le fichier .env et le personnaliser
cp .env .env.local
```

Configurer la base de données dans le fichier `.env.local` selon votre configuration locale :

```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/recurring?serverVersion=8.0"
```

```bash
# 4. Créer la base de données
php bin/console doctrine:database:create

# 5. Exécuter les migrations
php bin/console doctrine:migrations:migrate

# 6. Démarrer le serveur de développement Symfony
symfony serve
```

### Accès à l’application

Une fois le serveur démarré, ouvrez votre navigateur à l’adresse :

http://localhost:8000

---

## 🗂️ Structure du projet

```
src/
  Entity/         # RecurringTask, Log, LinkedFile
  Controller/     # RecurringTaskController, LogController
  Form/           # RecurringTaskForm, LogForm
  Service/        # DateToDisplay (logique d’affichage des dates)
templates/
  recurring_task/ # Templates Twig pour les tâches
  log/            # Templates Twig pour les journaux
public/           # Point d’entrée de l’application
```

---

## 🖼️ Aperçu


![Aperçu de l'interface](screenshot.png)

---

## 📄 Licence

Ce projet est distribué sous la licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus d’informations.

---

## 📬 Contact

Pour toute question ou suggestion : [https://dwap.fr](https://dwap.fr)
