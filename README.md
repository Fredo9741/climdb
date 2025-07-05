# 🌡️ ClimDB - Système de Gestion d'Équipements Climatiques

**ClimDB** est une application web moderne développée en Laravel pour la gestion complète d'équipements de climatisation et de réfrigération. Elle offre une interface intuitive pour le suivi des équipements, la gestion des interventions, et l'administration des relevés de mesures.

## 🚀 Fonctionnalités

### 📋 Gestion Complète CRUD
- **Clients** : Gestion des entreprises et contacts
- **Sites** : Localisation et organisation des équipements
- **Modèles d'équipements** : Catalogue des différents types d'équipements
- **Équipements** : Inventaire détaillé avec historique
- **Pannes** : Suivi des dysfonctionnements
- **Interventions** : Planification et suivi des réparations
- **Devis** : Génération et gestion des estimations
- **Factures** : Facturation et suivi des paiements

### 🔧 Administration Avancée
- **Modèles de Relevés** : Interface d'administration pour créer et gérer les templates de mesures
- **Gestion dynamique** : Ajout/suppression d'éléments de mesure avec validation
- **Templates pré-configurés** : Modèles pour maintenance préventive, diagnostic de panne, et mise en service

### 📊 Données TDF Intégrées
- **93 équipements** répartis sur **33 sites** à La Réunion
- **Données réelles** extraites des documents techniques
- **Seeders automatiques** pour initialisation rapide

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 10+** - Framework PHP moderne
- **PHP 8.1+** - Dernière version stable
- **MySQL** - Base de données relationnelle
- **Eloquent ORM** - Gestion des relations de données

### Frontend
- **Vue.js 3** - Framework JavaScript réactif
- **Inertia.js** - Architecture SPA sans API
- **Tailwind CSS** - Framework CSS utilitaire
- **Vite** - Bundler moderne et rapide

### Outils de Développement
- **Composer** - Gestionnaire de dépendances PHP
- **NPM** - Gestionnaire de paquets JavaScript
- **Pest** - Framework de test PHP
- **ESLint** - Linter JavaScript

## 📦 Installation

### Prérequis
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL
- Git

### Étapes d'installation

1. **Cloner le projet**
```bash
git clone https://github.com/Fredo9741/climdb.git
cd climdb
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
```

4. **Configuration de l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurer la base de données**
Modifier le fichier `.env` avec vos paramètres de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=climdb
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

6. **Migrations et seeders**
```bash
php artisan migrate
php artisan db:seed
```

7. **Compilation des assets**
```bash
npm run build
```

8. **Démarrage du serveur**
```bash
php artisan serve
```

## 👤 Authentification

### Comptes par défaut
- **Administrateur** : `admin@example.com` / `password`
- **Utilisateur** : `user@example.com` / `password`

### Rôles et permissions
- **Admin** : Accès complet à toutes les fonctionnalités
- **Technicien** : Accès aux pannes et interventions
- **Utilisateur** : Accès en lecture seule

## 🗂️ Structure du Projet

```
climdb/
├── app/
│   ├── Http/Controllers/     # Contrôleurs de l'application
│   ├── Models/              # Modèles Eloquent
│   └── ...
├── database/
│   ├── migrations/          # Migrations de base de données
│   └── seeders/            # Seeders avec données TDF
├── resources/
│   ├── js/                 # Composants Vue.js
│   │   ├── components/     # Composants réutilisables
│   │   ├── layouts/        # Layouts de l'application
│   │   └── pages/          # Pages Vue.js
│   └── views/              # Templates Blade
└── routes/                 # Définition des routes
```

## 🔧 Fonctionnalités Techniques

### Architecture
- **MVC** : Séparation claire des responsabilités
- **Repository Pattern** : Abstraction de la couche de données
- **Service Layer** : Logique métier encapsulée
- **Request Validation** : Validation des données entrantes

### Sécurité
- **CSRF Protection** : Protection contre les attaques CSRF
- **XSS Prevention** : Échappement automatique des données
- **Input Sanitization** : Nettoyage des données utilisateur
- **Authentication** : Système d'authentification Laravel

### Performance
- **Eager Loading** : Optimisation des requêtes N+1
- **Database Indexing** : Index sur les colonnes critiques
- **Asset Optimization** : Minification et compression
- **Caching** : Mise en cache des requêtes fréquentes

## 📱 Interface Utilisateur

### Design Moderne
- **Responsive Design** : Adaptable à tous les écrans
- **Dark/Light Mode** : Thème sombre/clair
- **Composants UI** : Bibliothèque de composants réutilisables
- **Navigation Intuitive** : Sidebar et navigation par onglets

### Expérience Utilisateur
- **Validation en temps réel** : Feedback instantané
- **Messages de succès/erreur** : Notifications contextuelles
- **Chargement optimisé** : États de chargement fluides
- **Accessibilité** : Respect des standards WCAG

## 🧪 Tests

### Exécution des tests
```bash
# Tests PHP
php artisan test

# Tests JavaScript
npm run test

# Tests avec couverture
php artisan test --coverage
```

### Types de tests
- **Unit Tests** : Tests unitaires des modèles et services
- **Feature Tests** : Tests d'intégration des fonctionnalités
- **Browser Tests** : Tests end-to-end avec Laravel Dusk

## 🚀 Déploiement

### Environnement de production
```bash
# Optimisations pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Variables d'environnement
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com
```

## 🤝 Contribution

Les contributions sont les bienvenues ! Voici comment contribuer :

1. **Fork** le projet
2. **Créer** une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. **Commiter** vos changements (`git commit -m 'Add some AmazingFeature'`)
4. **Pousser** vers la branche (`git push origin feature/AmazingFeature`)
5. **Ouvrir** une Pull Request

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 📞 Support

Pour toute question ou problème :
- **Issues GitHub** : [Créer un issue](https://github.com/Fredo9741/climdb/issues)
- **Email** : fredo9741@example.com

## 🙏 Remerciements

- **Laravel** - Framework PHP exceptionnel
- **Vue.js** - Framework JavaScript réactif
- **Tailwind CSS** - Framework CSS utilitaire
- **Inertia.js** - Architecture SPA moderne
- **TDF** - Données d'équipements réelles

---

Développé avec ❤️ par [Fredo9741](https://github.com/Fredo9741) 