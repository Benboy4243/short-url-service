# Architecture du projet Short URL

## 1. Structure des dossiers
short-url/
│
├── api/                    # Code PHP du backend
│   ├── public/             # Point d'entrée de l'API
│   ├── src/
│   │   ├── Controllers/    # Logique des endpoints
│   │   ├── Services/       # Services métiers (slug, validation, DB)
│   │   ├── Models/         # Modèles de données
│   │   └── Config/         # Configuration DB, constants
│   └── .htaccess           # Config Apache (vide pour l'instant)
│
├── database/
│   └── schema.sql           # Structure de la base de données
│
├── docs/
│   └── architecture.md
│
├── frontend/               # Frontend moderne Next.js / React (App Router)
│   ├── app/                # Pages App Router
│   │   ├── globals.css
│   │   ├── layout.tsx
│   │   └── page.tsx        # Page principale "/"
│   ├── components/         # Composants React (UrlForm, Alert, etc.)
│   ├── lib/                # Fonctions utilitaires (API fetch)
│   │   └── api.ts
│   ├── styles/             # CSS spécifiques aux composants
│   ├── public/             # Assets (images, favicon, etc.)
│   ├── next.config.js      # Configuration Next.js
│   └── tsconfig.json       # Config TypeScript
│
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
2. Accéder à l’interface: http://localhost:3000
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
