FROM banderson/drupal

MAINTAINER brendan_anderson@hcpss.org
LABEL vendor="Howard County Public School System"
LABEL org.hcpss.purchasing.version="1.0.0"

# SQLite for storing SimpleSAMLphp sessions.
RUN apt-get update && apt-get install -y \
        sqlite3 \
        php-sqlite3 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p /srv/sqlite
    
COPY docker/web/config/simplesaml /srv/simplesaml_config
COPY docker/web/config/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Create the Drupal structure.
COPY drupal/web/autoload.php   /var/www/drupal/web/autoload.php
COPY drupal/web/modules/custom /var/www/drupal/web/modules/custom
COPY drupal/web/themes/custom  /var/www/drupal/web/themes/custom
COPY drupal/scripts            /var/www/drupal/scripts
COPY drupal/drush              /var/www/drupal/drush
COPY drupal/composer.json      /var/www/drupal/composer.json
COPY drupal/composer.lock      /var/www/drupal/composer.lock
COPY drupal/config             /var/www/drupal/config

RUN mkdir /var/www/drupal/files && chown www-data:www-data /var/www/drupal/files

RUN composer install -d /var/www/drupal

COPY docker/web/entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
