# Purchasing

This is the drupal website for the HCPSS purchasing department.

## Development

## To bring up a development version of the site:

### 1. Prerequisites

1. You need docker and docker-compose installed.
2. You need this in your /etc/hosts file: `127.0.0.1 purchasing.hcpss.localhost`
3. You need to have your aws credentials stored in ~/.aws/credentials

### 1. Clone this repository

```
$ git clone https://github.com/HCPSS/purchasing.hcpss.org.git
$ cd purchasing.hcpss.org
```

### 2. Initialize the project

We need 3 things for our project to run that are not in VCS:

1. A copy of the production database placed at /drupal.sql
2. A copy of the environment variables at /.env
3. All source code managed by composer.

I made a bash script that will do all 3 of these.

```
$ ./bin/init
```

### 3. Launch

```
$ docker-compose up -d
```

The site is now available at http://purchasing.hcpss.localhost:8086 \
username: webmaster \
password: admin

## Performing updates.

### 1. Prerequisites

1. All prerequisites from step 1 above.
2. You need to be logged in to reg.hcpss.org. You can do this 
by typing `docker login reg.hcpss.org` into the terminal.

### 2. Launch the development environment

This is done my following steps 1-3 above.

### 3. Update dependencies

```
$ docker exec purchasing_web composer update
```

Watch the output and make sure that the things you are expecting to update are
getting updated.

### 4. Update drupal

```
$ docker exec purchasing_web drush --root=/var/www/drupal/web updb -y
```

Make sure we don't get any errors.

### 5. Test the site.

Poke around and make sure nothing goes wrong when browsing. Also make sure to
sumbit an purchasing request and make sure it gets saved.

### 6. Export your config changes

The update process modifies config so we need to export it:

```
$ docker exec purchasing_web drush --root=/var/www/drupal/web csex -y
```

### 7. Commit your chenges to VCS

```
$ git add .
$ git commit -m "Update drupal"
$ git push origin master
```

### 8. Rebuild your image(s)

The new code/config needs to be packaged into a docker image.

```
$ docker-compose up -d --build
```

### 9. Push the new images to reg.hcpss.org

```
$ docker-compose push
```

## Deploying

### 1. Go to your deploy location

SSH into your server and cd into the directory that houses the docker-compose.yml file.

### 2. Pull the new image(s)

```
$ docker-compose pull
```

If you get an error, you might not be logged in to reg.hcpss.org. Do so with:

```
$ docker login reg.hcpss.org
```

### 3 Updated the container(s)

```
$ docker-compose up -d
```

The docker entrypoint takes care of importing the new config and performing the updates. You can monitor the progress with:

```
$ docker logs -f purchasing_web
```