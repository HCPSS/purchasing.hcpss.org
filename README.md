# HCPSS Purchasing

## To bring the site up

### 1. Clone this repository

```
$ git clone https://github.com/HCPSS/purchasing.hcpss.org.git
$ cd purchasing.hcpss.org
```

### 2. Start the containers

```
$ docker-compose up -d
```

### 3. Download the composer dependencies

```
$ docker exec -it purchasinghcpssorg_drupal_1 composer install
```

*purchasinghcpssorg_drupal_1* is the name of the drupal container. This is 
generated based on a couple of thing, one of which is the name of the parent 
folder. So if you changed the name of the folder it might be slightly different. 
You can always see your container names by running `docker ps`

### 4. Install Drupal

```
$ docker exec -it purchasinghcpssorg_drupal_1 drush @dev si -y \
	--account-pass=admin \
	--db-url=mysql://drupal:drupal@drupal_db/drupal
```

Obviously, you can set *--account-pass* to whatever you want.

### 5. Enable the theme and module(s)

```
$ docker exec -it purchasinghcpssorg_drupal_1 drush @dev en -y \
	purchasing_parity \
	purchasing \
	purchasing_sample_data
```

After this it might be a good idea to clear the cache: `docker exec -it
piahcpssorg_drupal_1 drush @dev cr -y`

### 6. Visit the site

Visit *[http://localhost:8084](http://localhost:8084)*.

