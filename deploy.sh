#!/bin/bash

# Script de déploiement automatique pour CMS multilingue Symfony
# Serveur: symfonyvite.simpify.pro

echo "🚀 Début du déploiement automatique..."

# Configuration FTP
FTP_HOST="46.202.129.197"
FTP_USER="user_symfonyvite"
FTP_PASS="eAaGl6vpl|c7Gv5P9"
FTP_DIR="/home/user/web/symfonyvite.simpify.pro"

# Répertoire du projet
PROJECT_DIR="/workspace/symfony-multilingual-cms"

echo "📁 Préparation du projet..."
cd "$PROJECT_DIR"

# Nettoyer le cache
echo "🧹 Nettoyage du cache..."
php bin/console cache:clear --env=prod --no-debug

# Optimiser l'autoloader
echo "⚡ Optimisation de l'autoloader..."
php composer.phar dump-autoload --optimize --no-dev --classmap-authoritative

echo "📦 Création de l'archive de déploiement..."
# Créer un fichier temporaire avec la liste des fichiers à exclure
cat > .deployignore << EOF
.git/
node_modules/
var/cache/
var/log/
.env.local
.env.*.local
deploy.sh
.deployignore
EOF

# Créer une archive tar.gz avec tous les fichiers nécessaires
tar -czf symfony-cms-deploy.tar.gz \
    --exclude-from=.deployignore \
    --exclude='*.log' \
    --exclude='var/cache/*' \
    --exclude='var/log/*' \
    .

echo "🌐 Connexion au serveur FTP et upload..."

# Créer un fichier de configuration LFTP temporaire
cat > .lftprc << EOF
set ftp:ssl-allow no
set ftp:ssl-protect-data no
set ssl:verify-certificate no
EOF

# Utiliser LFTP pour le déploiement avec échappement du mot de passe
lftp -c "
source .lftprc
open $FTP_HOST
user $FTP_USER '$FTP_PASS'
cd $FTP_DIR
put symfony-cms-deploy.tar.gz
quit
"

# Vérifier si l'upload a réussi
if [ $? -eq 0 ]; then
    echo "✅ Archive uploadée avec succès!"
    
    # Se connecter au serveur pour extraire l'archive
    echo "📂 Extraction de l'archive sur le serveur..."
    lftp -c "
    source .lftprc
    open $FTP_HOST
    user $FTP_USER '$FTP_PASS'
    cd $FTP_DIR
    quote SITE EXEC tar -xzf symfony-cms-deploy.tar.gz
    rm symfony-cms-deploy.tar.gz
    quit
    "
    
    echo "🎉 Déploiement terminé avec succès!"
    echo "🌍 Votre CMS multilingue est maintenant disponible sur: https://symfonyvite.simpify.pro"
    
else
    echo "❌ Erreur lors de l'upload!"
    exit 1
fi

# Nettoyer les fichiers temporaires
rm -f symfony-cms-deploy.tar.gz .deployignore .lftprc

echo "✨ Déploiement automatique terminé!"
