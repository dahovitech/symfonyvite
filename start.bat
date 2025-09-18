@echo off
title CMS Multilingue Symfony/Vite

echo.
echo 🚀 Démarrage du CMS Multilingue Symfony/Vite
echo =============================================

REM Vérification des prérequis
where php >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ PHP n'est pas installé
    pause
    exit /b 1
)

where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Composer n'est pas installé
    pause
    exit /b 1
)

where node >nul 2>nul
if %errorlevel% neq 0 (
    echo ❌ Node.js n'est pas installé
    pause
    exit /b 1
)

REM Installation des dépendances si nécessaire
if not exist "vendor" (
    echo 📦 Installation des dépendances PHP...
    composer install
)

if not exist "node_modules" (
    echo 📦 Installation des dépendances Node.js...
    npm install
)

REM Configuration de la base de données
if not exist "var\data.db" (
    echo 🗄️ Configuration de la base de données...
    php bin/console doctrine:migrations:migrate --no-interaction
    php bin/console doctrine:fixtures:load --no-interaction
    echo ✅ Base de données configurée avec des données de démonstration
)

echo.
echo ✅ Application prête !
echo.
echo 🌐 Accès aux interfaces :
echo    • Frontend public: http://localhost:8000
echo    • Administration: http://localhost:8000/admin
echo    • Page de test: http://localhost:8000/welcome
echo.
echo 🔧 Pour le développement :
echo    • Démarrer le serveur: php -S localhost:8000 -t public/
echo    • Build Vite (dev): npm run dev
echo    • Build Vite (prod): npm run build
echo.
echo 📚 Données de démonstration :
echo    • 3 langues : Français (défaut), Anglais, Espagnol
echo    • 3 services d'exemple avec traductions
echo.

set /p answer="🎯 Démarrer le serveur maintenant ? (y/N): "
if /i "%answer%"=="y" (
    echo 🚀 Démarrage du serveur sur http://localhost:8000
    php -S localhost:8000 -t public/
) else (
    echo.
    echo 👋 À bientôt ! Utilisez 'php -S localhost:8000 -t public/' pour démarrer le serveur.
    pause
)
