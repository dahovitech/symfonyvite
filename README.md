# CMS Multilingue avec Symfony + Vite

## Description

Système de gestion de contenu multilingue robuste développé avec Symfony 7.1 et Vite. Ce CMS propose une interface d'administration ergonomique pour gérer des services avec du contenu traduit dans plusieurs langues, ainsi qu'un système de fallback intelligent.

## Fonctionnalités

### Gestion des langues
- ✅ Ajout/suppression de langues
- ✅ Activation/désactivation des langues
- ✅ Définition d'une langue par défaut
- ✅ Interface intuitive de gestion

### Gestion des services multilingues
- ✅ Création de services avec slug unique
- ✅ Interface à onglets pour gérer les traductions
- ✅ Contenu structuré (titre, description, détail)
- ✅ Publication/dépublication des services
- ✅ Fallback automatique vers la langue par défaut

### Interface frontend
- ✅ Sélecteur de langue dynamique
- ✅ Affichage adaptatif selon la langue
- ✅ Fallback intelligent en cas de traduction manquante
- ✅ Design responsive

### Interface d'administration
- ✅ Dashboard centralisé
- ✅ Navigation verticale intuitive
- ✅ Gestion des langues et services
- ✅ Interface optimisée pour la saisie multilingue

## Architecture technique

### Backend
- **Framework**: Symfony 7.1
- **Base de données**: SQLite (configurable pour PostgreSQL/MySQL)
- **ORM**: Doctrine
- **Structure**: Architecture en entités relationnelles

### Frontend
- **Build tool**: Vite 5.x
- **Plugin**: vite-plugin-symfony
- **Styles**: CSS natif avec design moderne
- **JavaScript**: Vanilla JS pour les interactions

### Entités principales
- `Language`: Gestion des langues disponibles
- `Service`: Services du site
- `ServiceTranslation`: Traductions des services par langue

## Installation et configuration

### Prérequis
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (ou PostgreSQL/MySQL)

### Installation

1. **Cloner et configurer le projet**
```bash
git clone https://github.com/dahovitech/symfonyvite.git
cd symfonyvite
composer install
npm install
```

2. **Configuration de la base de données**
```bash
# Créer la base de données et exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les données de démonstration
php bin/console doctrine:fixtures:load
```

3. **Développement**
```bash
# Terminal 1: Serveur Symfony
php -S localhost:8000 -t public/

# Terminal 2: Serveur de développement Vite
npm run dev
```

4. **Production**
```bash
# Build des assets
npm run build

# Le serveur web servira les fichiers depuis public/build/
```

## Utilisation

### Administration
Accédez à `/admin` pour gérer votre contenu :

1. **Gestion des langues** (`/admin/languages`)
   - Ajouter de nouvelles langues
   - Définir la langue par défaut
   - Activer/désactiver des langues

2. **Gestion des services** (`/admin/services`)
   - Créer des services multilingues
   - Éditer le contenu dans toutes les langues
   - Publier/dépublier des services

### Frontend public
- **Accueil** : `/` - Liste des services publiés
- **Service** : `/service/{slug}` - Détail d'un service
- **Changement de langue** : Sélecteur en haut à droite

## Données de démonstration

Le système est livré avec :
- 3 langues : Français (par défaut), Anglais, Espagnol
- 3 services d'exemple avec traductions complètes
- Interface d'administration prête à l'emploi

## Architecture du fallback

Le système de fallback fonctionne comme suit :
1. Affichage du contenu dans la langue sélectionnée
2. Si la traduction n'existe pas, affichage dans la langue par défaut
3. Si aucune traduction n'est disponible, message d'information

## Personnalisation

### Ajouter de nouveaux types de contenu
1. Créer une nouvelle entité avec ses traductions
2. Ajouter les contrôleurs d'administration
3. Créer les templates correspondants

### Modifier l'interface
- **Styles** : Modifier `assets/styles/app.css`
- **Templates** : Éditer les fichiers `.html.twig`
- **JavaScript** : Ajouter des fonctionnalités dans `assets/app.js`

## Configuration avancée

### Base de données
Pour utiliser PostgreSQL ou MySQL, modifiez `DATABASE_URL` dans `.env`:
```env
# PostgreSQL
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/cms_db"

# MySQL
DATABASE_URL="mysql://user:password@127.0.0.1:3306/cms_db"
```

### Vite
Configuration dans `vite.config.js` pour personnaliser le build.

## Support et contribution

- **Issues** : Rapportez les problèmes sur GitHub
- **Pull Requests** : Les contributions sont les bienvenues
- **Documentation** : Consultez la documentation Symfony pour les fonctionnalités avancées

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.

---

Développé avec ❤️ par Prudence ASSOGBA
