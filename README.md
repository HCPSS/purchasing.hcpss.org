# Purchasing

This is the drupal website for the HCPSS purchasing department.

## Development

### Clone the repo

```
$ git clone https://github.com/HCPSS/purchasing.hcpss.org.git
$ cd purchasing.hcpss.org
```

### Create an .env file

The .env file holds our secrets.

```
$ cp example.env .env
```

Edit to your taste.

### Launch it

```
$ docker-compose up -d
```

### Install Drupal

```
$ docker exec purchasing_web drush @self.dev site:install -y \
    --account-pass=admin \
    --db-url=mysql://drupal:drupal@drupal_db:3306/drupal \
    --config-dir=../config/sync minimal
    
$ docker exec purchasing_web drush @self.dev config:import -y
$ docker exec purchasing_web drush @self.dev cache:rebuild
```

Change parameters to match what you have in your .env file.

### Generate dummy content (optional)

```
$ docker exec purchasing_web drush @self.dev purchasing:generate:all
$ docker exec purchasing_web drush @self.dev cache:rebuild
```

### Make your changes

Code changes should be made in drupal/web/modules/custom/purchasing. Theme
changes should be made in drupal/web/themes/custom/parity_purchasing.

Changes made to configuration (changes made through the UI) need to be 
exported:

```
$ docker exec purchasing_web drush @self.dev config:export -y
```
