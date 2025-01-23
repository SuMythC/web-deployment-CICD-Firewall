#!/bin/bash
if [ -f /var/www/html/index.php ]; then
    echo "Removing existing index.php"
    sudo rm -f /var/www/html/index.php
else
    echo "No existing index.html found, nothing to remove."
fi
