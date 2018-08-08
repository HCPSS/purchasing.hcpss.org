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
$ docker exec purchasing_web drush @self.dev site:install config_installer -y \
    --account-pass=admin \
    config_installer_sync_configure_form.sync_directory=/var/www/drupal/config/sync
```

### Generate dummy content (optional)

```
$ docker exec purchasing_web drush @self.dev purchasing:generate:all
$ docker exec purchasing_web drush @self.dev search-api:index
```

### Make your changes

Code changes should be made in drupal/web/modules/custom/purchasing. Theme
changes should be made in drupal/web/themes/custom/parity_purchasing.

Changes made to configuration (changes made through the UI) need to be 
exported using the config split module:

```
$ docker exec purchasing_web drush @self.dev config-split:export -y
```

### Deploy your changes

Once your changes have been made and checked into version control, rebuild the
reg.hcpss.org/purchasing/web image:

```
$ docker-compose up -d --build
```

Once the image has rebuilt, push it to the registry:

```
$ docker push reg.hcpss.org/purchasing/web
```

Go to the server you want to deploy to (staging or production) and pull the new
image:

```
$ docker pull reg.hcpss.org/purchasing/web
```

And update the container(s):

```
$ cd /folder/with/docker-compose && docker-compose up -d
```

I like to watch the update scripts run so I usually follow the logs like this:

```
$ cd /folder/with/docker-compose && docker-compose up -d && docker logs -f purchasing_web
```
