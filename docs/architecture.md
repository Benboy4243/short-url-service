# Architecture du projet Short URL


## 1. Structure des dossiers
short-url/
│
├── api/ # Code PHP du backend
│ ├── public/ # Point d'entrée de l'API
│ ├── src/
│ │ ├── Controllers/
│ │ ├── Services/
│ │ ├── Models/
│ │ └── Config/
│ └── .htaccess
├── database/
│ └── schema.sql # Structure de la base de données
├── docs/
│ └── architecture.md
└── README.md


## 2. API
- `GET /{slug}` → redirection vers l'URL originale
- `POST /shorten` → création d’un nouveau lien court


## 3. Base de données
Table principale : `short_urls`
| Colonne       | Type        | Description                     |
|---------------|-------------|---------------------------------|
| id            | INT         | Clé primaire auto-incrémentée   |
| slug          | VARCHAR(16) | Identifiant unique du lien      |
| original_url  | TEXT        | URL complète                    |
| created_at    | DATETIME    | Date de création                |
| expires_at    | DATETIME    | Date d'expiration (optionnel)   |
| clicks        | INT         | Nombre de redirections          |


## 4. Lancer le projet localement
1. Installer XAMPP
2. Démarrer Apache et MySQL
3. Importer `database/schema.sql` dans phpMyAdmin # Pour y accéder faire http://localhost/phpmyadmin
4. Lancer le serveur local:
```bash
cd api/public
php -S localhost:8000 
```

## 5. Extensions Vs Code recommandées:
- PHP Intelephense
- PHP Server
- PHP Debug
- EditorConfig
