#!/usr/bin/env bash

export BACKUP_FILE=$(
    docker run \
        --volume ~/.aws:/root/.aws \
        cgswong/aws \
        aws s3 ls hcpss.web.backups/purchasing/ | tail -n 1 | awk '{print $NF}'
)

mkdir -p ./fetchseed

docker run \
    --volume ~/.aws:/root/.aws \
    --volume $(pwd)/fetchseed:/app \
    cgswong/aws \
    aws s3 cp s3://hcpss.web.backups/purchasing/$BACKUP_FILE /app/$BACKUP_FILE

cd ./fetchseed
tar -xf ./$BACKUP_FILE
mv ./drupal.sql ../drupal.sql
cd ..
rm -rf fetchseed
