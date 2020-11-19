#!/usr/bin/env bash

chown -R www-data:www-data /var/www/drupal/files
chown -R www-data:www-data /var/www/drupal/web/sites/default/files
chown -R www-data:www-data /var/www/drupal/config
chown -R www-data:www-data /srv/sqlite

chown root:root /var/www/drupal/web/sites/default/services.yml
chown root:root /var/www/drupal/web/sites/default/settings.php
chmod 444 /var/www/drupal/web/sites/default/services.yml
chmod 444 /var/www/drupal/web/sites/default/settings.php

# Wait for MySQL
while ! mysqladmin ping -h"$MYSQL_HOSTNAME" --silent; do
    echo "Waiting for database connection..."
    sleep 5
done

drush --root=/var/www/drupal/web cc drush
drush --root=/var/www/drupal/web cr
drush --root=/var/www/drupal/web cim -y
drush --root=/var/www/drupal/web cr
drush --root=/var/www/drupal/web updb -y
drush --root=/var/www/drupal/web search-api:clear
drush --root=/var/www/drupal/web search-api:index
drush --root=/var/www/drupal/web cr

exec "$@"
