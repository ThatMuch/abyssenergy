#!/bin/bash

# Script de développement pour le thème enfant SCSS
# Usage: ./dev.sh [watch|build|lint|help]

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction d'aide
show_help() {
    echo -e "${BLUE}🎨 Script de développement SCSS pour abyssenergy${NC}"
    echo ""
    echo "Usage: ./dev.sh [commande]"
    echo ""
    echo "Commandes disponibles:"
    echo -e "  ${GREEN}watch${NC}   - Surveille les fichiers SCSS et compile automatiquement"
    echo -e "  ${GREEN}build${NC}   - Compile le SCSS en mode production (minifié)"
    echo -e "  ${GREEN}dev${NC}     - Compile en mode développement (compressed)"
    echo -e "  ${GREEN}lint${NC}    - Vérifie la syntaxe SCSS"
    echo -e "  ${GREEN}fix${NC}     - Corrige automatiquement les erreurs de style"
    echo -e "  ${GREEN}clean${NC}   - Supprime les fichiers CSS générés"
    echo -e "  ${GREEN}install${NC} - Installe les dépendances npm"
    echo -e "  ${GREEN}start${NC}   - Lance la compilation SCSS et BrowserSync pour un rechargement automatique"
    echo -e "  ${GREEN}help${NC}    - Affiche cette aide"
    echo ""
    echo "Exemples:"
    echo "  ./dev.sh watch   # Lance la surveillance des fichiers"
    echo "  ./dev.sh build   # Compile pour la production"
}

# Vérifier si npm est installé
check_npm() {
    if ! command -v npm &> /dev/null; then
        echo -e "${RED}❌ npm n'est pas installé. Veuillez installer Node.js et npm.${NC}"
        exit 1
    fi
}

# Vérifier si les dépendances sont installées
check_dependencies() {
    if [ ! -d "node_modules" ]; then
        echo -e "${YELLOW}⚠️  Les dépendances npm ne sont pas installées.${NC}"
        echo -e "${BLUE}📦 Installation des dépendances...${NC}"
        npm install
    fi
}

# Fonction principale
case "${1:-help}" in
    "watch")
        echo -e "${BLUE}👀 Démarrage de la surveillance SCSS...${NC}"
        check_npm
        check_dependencies
        echo -e "${GREEN}✅ Surveillance active. Ctrl+C pour arrêter.${NC}"
        npm run watch
        ;;

    "build")
        echo -e "${BLUE}🏗️  Compilation en mode production...${NC}"
        check_npm
        check_dependencies
        npm run build
        echo -e "${GREEN}✅ Compilation terminée !${NC}"
        ;;

    "dev")
        echo -e "${BLUE}🛠️  Compilation en mode développement...${NC}"
        check_npm
        check_dependencies
        sass scss/style.scss:style.min.css --style compressed --source-map
        echo -e "${GREEN}✅ Compilation terminée !${NC}"
        ;;

    "build-dev")
        echo -e "${BLUE}🛠️  Compilation en mode développement (sans minification)...${NC}"
        check_npm
        check_dependencies
        sass scss/style.scss:style.min.css --style compressed
        echo -e "${GREEN}✅ Compilation terminée !${NC}"
        ;;

    "lint")
        echo -e "${BLUE}🔍 Vérification du code SCSS...${NC}"
        check_npm
        check_dependencies
        npm run lint
        ;;

    "fix")
        echo -e "${BLUE}🔧 Correction automatique des erreurs...${NC}"
        check_npm
        check_dependencies
        npm run lint:fix
        echo -e "${GREEN}✅ Corrections appliquées !${NC}"
        ;;

    "clean")
        echo -e "${BLUE}🧹 Nettoyage des fichiers CSS...${NC}"
        rm -f style.min.css style.min.css.map
        echo -e "${GREEN}✅ Fichiers nettoyés !${NC}"
        ;;

    "install")
        echo -e "${BLUE}📦 Installation des dépendances...${NC}"
        check_npm
        npm install
        echo -e "${GREEN}✅ Dépendances installées !${NC}"
        ;;

    "start")
        echo -e "${BLUE}🚀 Démarrage de l'environnement de développement avec rechargement automatique...${NC}"
        check_npm
        check_dependencies
        echo -e "${YELLOW}👀 Surveillance active avec BrowserSync. Ctrl+C pour arrêter.${NC}"
        npm run start
        ;;

    "help"|*)
        show_help
        ;;
esac
