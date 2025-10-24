# Planning App v3

planning_app_v3/
│
├── index.php                      ← Page principale (interface du planning)
│
├── README.md                      ← Explications, installation, SQL de la table `plannings`
│
├── config/
│   └── db.php                     ← Connexion PDO à la base MySQL
│
├── models/
│   ├── Employee.php               ← Gestion des employés (récupère depuis `main_employees`)
│   └── Planning.php               ← Gestion des plannings (CRUD sur la table `plannings`)
│
├── api/
│   ├── employees.php              ← Endpoint JSON → liste des employés (pour la liste déroulante)
│   └── events.php                 ← Endpoint JSON → CRUD pour les événements (create, read, update, delete)
│
├── assets/
│   ├── css/
│   │   └── style.css              ← Style personnalisé (complète Bootstrap)
│   └── js/
│       └── main.js                ← Logique JS : FullCalendar + modals + appels API
│
└── sql/
    └── plannings.sql              ← Script de création de la table `plannings`



## 🚀 Installation
1. Copier le dossier `planning_app_v3` sur votre serveur PHP (Apache/Nginx).
2. Configurer la connexion à la base MySQL dans `config.php`.
3. Créer la table `planning` :
```sql
CREATE TABLE planning (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  employee_id BIGINT NOT NULL,
  start DATETIME NOT NULL,
  end DATETIME NOT NULL,
  comment TEXT
);
```
4. Ouvrir `index.php` dans votre navigateur.

## ✨ Fonctionnalités
- Création / modification / suppression via modal Bootstrap.
- Vue hebdomadaire et journalière.
- Données dynamiques depuis la base MySQL.
