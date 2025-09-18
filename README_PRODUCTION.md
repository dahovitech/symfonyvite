# CMS Multilingue Symfony - Version Production

## 🌍 Accès au CMS

- **URL Frontend:** https://symfonyvite.simpify.pro
- **URL Admin:** https://symfonyvite.simpify.pro/admin

## 🔧 Configuration Production

Le CMS est configuré avec :
- Base de données SQLite (prête à l'emploi)
- Cache optimisé pour la production
- Autoloader optimisé
- Données de démonstration préchargées

## 🗂️ Structure Admin

1. **Gestion des langues :** `/admin/language`
   - Ajouter, modifier, supprimer des langues
   - Définir la langue par défaut

2. **Gestion des services :** `/admin/service`
   - Créer, éditer des services multilingues
   - Interface à onglets pour chaque langue

## 🌐 Fonctionnalités Frontend

- Sélecteur de langue dynamique
- Fallback intelligent vers la langue par défaut
- Interface responsive et moderne

## 🚀 Déploiement

Le déploiement se fait automatiquement via GitHub Actions lors des push sur la branche master.

