# Architecture du projet Short URL

## 1. Structure des dossiers
short-url-service/
├── .git/                                  # Dossier Git (historique & configuration)
├── .gitignore                             # Fichier d'exclusion Git
├── README.md                              # Documentation principale
│
├── api/                                   # Backend PHP
│   ├── index.php                          # Point d'entrée API
│   └── src/
│       ├── Config/
│       │   └── database.php               # Configuration de la base de données
│       ├── Controllers/
│       ├── Models/
│       └── Services/
│           ├── Database.php               # Service de gestion BD
│
├── database/                              # Base de données
│   └── schema.sql                         # Schéma SQL (création tables)
│
├── docs/                                  # Documentation
│   └── architecture.md                    # Description de l'architecture
│
└── frontend/                              # Frontend React + TypeScript + Vite
    ├── index.html                         # Fichier HTML principal
    ├── package.json                       # Dépendances Node.js
    ├── vite.config.ts                     # Configuration Vite
    ├── eslint.config.js                   # Configuration ESLint
    ├── tsconfig.json                      # Configuration TypeScript global
    ├── tsconfig.app.json                  # Configuration TypeScript app
    ├── tsconfig.node.json                 # Configuration TypeScript Node
    ├── README.md                          # Documentation frontend
    ├── public/                            # Fichiers statiques
    └── src/
        ├── main.tsx                       # Point d'entrée React
        ├── index.css                      # Styles globaux
        ├── assets/                        # Ressources (images, etc.)
        ├── components/                    # Composants React
        ├── lib/
        │   └── api.ts                     # Client API
        └── styles/



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

### Backend PHP
1. Installer XAMPP
2. Démarrer Apache et MySQL
3. Importer `database/schema.sql` dans phpMyAdmin # Pour y accéder faire http://localhost/phpmyadmin
4. Lancer le serveur local:
```bash
cd api/public
php -S localhost:8000 
```

### Frontend Next.js
1. Installer Node.js dans le dossier frontend:
```bash
cd frontend
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
npm install
npm run dev
```
2. Accéder à l’interface: http://localhost:5173
3. Les appels API se font vers http://localhost:8000


## 5. Extensions VS Code recommandées
PHP Intelephense
PHP Server
PHP Debug
EditorConfig
ESLint
PHP Intelephense

## 6. Workflow Git recommandé
Créer une branche par fonctionnalité (feature/slug-generation, feature/frontend-ui, etc.)
Commit régulier + messages clairs
Merge dans main après revue par un autre membre (Faire des Pull Request)

Déploiement futur:
Backend → PlanetHoster
Frontend → À voir
