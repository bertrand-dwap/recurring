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

```bash
git clone https://github.com/bertrand-dwap/recurring.git
cd recurring
composer install
cp .env .env.local
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start
```

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
