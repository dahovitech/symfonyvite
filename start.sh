#!/bin/bash

# Script de démarrage pour le CMS Multilingue Symfony/Vite

echo "🚀 Démarrage du CMS Multilingue Symfony/Vite"
echo "============================================="

# Vérification des prérequis
if ! command -v php &> /dev/null; then
    echo "❌ PHP n'est pas installé"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    echo "❌ Composer n'est pas installé"
    exit 1
fi

if ! command -v node &> /dev/null; then
    echo "❌ Node.js n'est pas installé"
    exit 1
fi

# Installation des dépendances si nécessaire
if [ ! -d "vendor" ]; then
    echo "📦 Installation des dépendances PHP..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "📦 Installation des dépendances Node.js..."
    npm install
fi

# Configuration de la base de données
if [ ! -f "var/data.db" ]; then
    echo "🗄️ Configuration de la base de données..."
    php bin/console doctrine:migrations:migrate --no-interaction
    php bin/console doctrine:fixtures:load --no-interaction
    echo "✅ Base de données configurée avec des données de démonstration"
fi

echo ""
echo "✅ Application prête !"
echo ""
echo "🌐 Accès aux interfaces :"
echo "   • Frontend public: http://localhost:8000"
echo "   • Administration: http://localhost:8000/admin"
echo "   • Page de test: http://localhost:8000/welcome"
echo ""
echo "🔧 Pour le développement :"
echo "   • Démarrer le serveur: php -S localhost:8000 -t public/"
echo "   • Build Vite (dev): npm run dev"
echo "   • Build Vite (prod): npm run build"
echo ""
echo "📚 Données de démonstration :"
echo "   • 3 langues : Français (défaut), Anglais, Espagnol"
echo "   • 3 services d'exemple avec traductions"
echo ""

# Démarrage optionnel du serveur
read -p "🎯 Démarrer le serveur maintenant ? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🚀 Démarrage du serveur sur http://localhost:8000"
    php -S localhost:8000 -t public/
fi
