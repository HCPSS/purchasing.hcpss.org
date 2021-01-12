FROM banderson/drupal:base

LABEL maintainer="Brendan Anderson <brendan_anderson@hcpss.org>"
LABEL vendor="Howard County Public School System"
LABEL org.hcpss.purchasing.web.version="0.1.4"

ENV DRUPAL_ENV="dev"

# SQLite for storing SimpleSAMLphp sessions.
RUN apt-get update && apt-get install -y \
        sqlite3 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p /srv/sqlite

# Down grade composer until 2.0 has better compatibility.
RUN composer self-update 1.10.17

COPY docker/web/config/php/php.ini             /usr/local/etc/php/php.ini
COPY docker/web/config/php/php.cli.ini         /usr/local/etc/php/php-cli.ini
COPY docker/web/config/simplesaml              /srv/simplesaml_config
COPY docker/web/config/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Create the Drupal structure.
COPY drupal/web/autoload.php               /var/www/drupal/web/autoload.php
COPY drupal/web/modules/custom             /var/www/drupal/web/modules/custom
COPY drupal/web/themes/custom              /var/www/drupal/web/themes/custom
COPY drupal/scripts                        /var/www/drupal/scripts
COPY drupal/drush                          /var/www/drupal/drush
COPY drupal/composer.json                  /var/www/drupal/composer.json
COPY drupal/composer.lock                  /var/www/drupal/composer.lock
COPY drupal/config                         /var/www/drupal/config
COPY drupal/load.environment.php           /var/www/drupal/load.environment.php
COPY drupal/web/sites/default/settings.php /var/www/drupal/web/sites/default/settings.php
COPY drupal/web/sites/default/services.yml /var/www/drupal/web/sites/default/services.yml

# Drush aliases
RUN mkdir -p /root/.drush/sites
COPY drupal/drush/sites/self.site.yml /root/.drush/sites/self.site.yml
COPY drupal/drush/drush.yml /root/.drush/drush.yml

RUN mkdir /var/www/drupal/files && chown www-data:www-data /var/www/drupal/files

RUN composer install -d /var/www/drupal

COPY docker/web/entrypoint.sh /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

EXPOSE 80

CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
