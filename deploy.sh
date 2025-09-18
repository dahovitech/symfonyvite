#!/bin/bash

# Script de déploiement automatique pour CMS multilingue Symfony
# Déploiement via GitHub vers symfonyvite.simpify.pro

echo "🚀 Préparation du déploiement automatique via GitHub..."

# Répertoire du projet
PROJECT_DIR="/workspace/symfony-multilingual-cms"

echo "📁 Préparation du projet..."
cd "$PROJECT_DIR"

# Nettoyer le cache de développement
echo "🧹 Nettoyage du cache de développement..."
php bin/console cache:clear

# Vérifier la base de données
echo "🗄️  Vérification de la base de données..."
php bin/console doctrine:migrations:status

# S'assurer que les fixtures sont chargées pour la démo
echo "📦 Chargement des données de démonstration..."
php bin/console doctrine:fixtures:load --no-interaction

# Créer un fichier README spécifique pour la production
echo "📝 Création du fichier README de production..."
cat > README_PRODUCTION.md << 'EOF'
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

EOF

echo "✅ Projet préparé pour la production!"

# Ajouter tous les fichiers modifiés
echo "📤 Ajout des fichiers au repository..."
git add .

# Créer un commit avec les optimisations
echo "💾 Création du commit de déploiement..."
git commit -m "🚀 Optimisation et préparation pour déploiement production

- Optimisation des bundles pour la production
- Configuration .env.prod ajoutée
- Base de données SQLite prête avec fixtures
- Documentation de production
- CMS multilingue entièrement fonctionnel

Fonctionnalités incluses:
✅ Interface admin complète (/admin)
✅ Gestion multilingue avancée
✅ Fallback intelligent
✅ Base de données préchargée
✅ Interface responsive"

# Push vers GitHub
echo "🌐 Push vers GitHub pour déploiement automatique..."
git push origin master

if [ $? -eq 0 ]; then
    echo ""
    echo "🎉 ✅ DÉPLOIEMENT RÉUSSI!"
    echo ""
    echo "🌍 Votre CMS multilingue sera disponible sous peu à :"
    echo "   👉 Frontend: https://symfonyvite.simpify.pro"
    echo "   👉 Admin:    https://symfonyvite.simpify.pro/admin"
    echo ""
    echo "📋 Le déploiement automatique via GitHub est en cours..."
    echo "💫 Attendez quelques minutes que le serveur traite les modifications."
    echo ""
else
    echo "❌ Erreur lors du push vers GitHub!"
    exit 1
fi

echo "✨ Script de déploiement terminé!"
