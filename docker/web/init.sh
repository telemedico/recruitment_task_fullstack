#!/bin/bash

VENDOR_DIR="/var/www/html/vendor"
NODE_MODULES_DIR="/var/www/html/node_modules"
WEBPACK_ENTRYPOINTS_FILE="/var/www/html/public/build/entrypoints.json"

RED="\033[0;31m"
GREEN="\033[0;32m"
BLUE="\033[0;34m"
PURPLE="\033[0;35m"

echo -e "
${PURPLE} ██████╗ ██████╗ ██╗███╗   ██╗████████╗ █████╗ ██████╗ ██╗     ███████╗
${PURPLE}██╔════╝██╔═══██╗██║████╗  ██║╚══██╔══╝██╔══██╗██╔══██╗██║     ██╔════╝
${PURPLE}██║     ██║   ██║██║██╔██╗ ██║   ██║   ███████║██████╔╝██║     █████╗
${PURPLE}██║     ██║   ██║██║██║╚██╗██║   ██║   ██╔══██║██╔══██╗██║     ██╔══╝
${PURPLE}╚██████╗╚██████╔╝██║██║ ╚████║   ██║   ██║  ██║██████╔╝███████╗███████╗
${PURPLE} ╚═════╝ ╚═════╝ ╚═╝╚═╝  ╚═══╝   ╚═╝   ╚═╝  ╚═╝╚═════╝ ╚══════╝╚══════╝
"

if [ ! -d "$VENDOR_DIR" ]; then
    echo -e "${BLUE}Vendor directory not found. Running composer install..."
    sleep 2

    if composer install; then
        echo -e "${GREEN}Composer install completed successfully."
        sleep 1
    else
        echo -e "${RED}Error: Composer install failed."
        sleep 1
        exit 1
    fi
fi

if [ ! -d "$NODE_MODULES_DIR" ]; then
    echo -e "${BLUE}Node modules directory not found. Running NPM install..."
    sleep 2

    if npm install; then
        echo -e "${GREEN}NPM install completed successfully."
        sleep 1
    else
        echo -e "${RED}Error: NPM install failed."
        sleep 1
        exit 1
    fi
fi

if [ ! -f "$WEBPACK_ENTRYPOINTS_FILE" ]; then
    echo -e "${BLUE}Webpack entrypoints file not found. Running build..."
    sleep 2

    if npm run build; then
        echo -e "${GREEN}Build completed successfully."
        sleep 1
    else
        echo -e "${RED}Error: Build failed."
        sleep 1
        exit 1
    fi
fi

echo -e "${BLUE}Starting Apache."
apache2-foreground
