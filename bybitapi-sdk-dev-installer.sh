#!/bin/bash

mkdir bybitapi-sdk-dev bybitapi-sdk-core bybitapi-sdk-spot bybitapi-sdk-derivatives bybitapi-sdk-websockets;

echo "Create bybitapi-sdk-core repository \n";
cd bybitapi-sdk-core;
git init && git remote add origin git@github.com:carpenstar/bybitapi-sdk-core.git && git fetch origin && git checkout master;

echo "Create bybitapi-sdk-spot repository \n";
cd ../bybitapi-sdk-spot;
git init && git remote add origin git@github.com:carpenstar/bybitapi-sdk-spot.git && git fetch origin && git checkout master;

echo "Create bybitapi-sdk-derivatives repository \n";
cd ../bybitapi-sdk-derivatives;
git init && git remote add origin git@github.com:carpenstar/bybitapi-sdk-derivatives.git && git fetch origin && git checkout master;

echo "Create bybitapi-sdk-websockets repository \n";
cd ../bybitapi-sdk-websockets;
git init && git remote add origin git@github.com:carpenstar/bybitapi-sdk-websockets.git && git fetch origin && git checkout master;


echo "Create bybitapi-sdk-dev environment directory \n";
cd ../bybitapi-sdk-dev;

echo "Create symlinks \n";
ln -s ../bybitapi-sdk-websockets/src/WebSockets ./WebSockets;
ln -s ../bybitapi-sdk-spot/src/Spot ./Spot;
ln -s ../bybitapi-sdk-derivatives/src/Derivatives ./Derivatives;
ln -s ../bybitapi-sdk-core/src/Core  ./Core;
ln -s ../bybitapi-sdk-core/src/BybitAPI.php  ./BybitAPI.php;


echo "Create composer.json file \n";
touch composer.json;

echo '{
    "name": "carpenstar/bybitapi-sdk-spot",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "Carpenstar",
            "email": "mighty.vlad@gmail.com"
        }
    ],
    "require": {
        "php": ">=7.4"
    },
    "require-dev": {
        "phpunit/phpunit": "^9.5"
    },
    "autoload": {
        "psr-4": {
            "Carpenstar\\\ByBitAPI\\\": "./"
        }
    }
}
' > composer.json;

echo "Create Dockerfile";
touch "Dockerfile";

echo 'FROM php:7.4-fpm

RUN apt-get update && apt-get install -y git zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
' > Dockerfile;


echo "Create docker-compose.yml file \n";
touch docker-compose.yml;

echo 'version: "3.1"

services:
  php:
    build: .
    working_dir: /var/www/html
    volumes:
      - ../bybitapi-sdk-websockets:/var/www/bybitapi-sdk-webosckets:rw
      - ../bybitapi-sdk-spot:/var/www/bybitapi-sdk-spot:rw
      - ../bybitapi-sdk-derivatives:/var/www/bybitapi-sdk-derivatives:rw
      - ../bybitapi-sdk-core:/var/www/bybitapi-sdk-core:rw
      - ./:/var/www/html:rw
' > docker-compose.yml;


echo "Create phpunit.xml file \n";
touch phpunit.xml;

echo '<phpunit bootstrap="vendor/autoload.php">
    <testsuites>
        <testsuite name="core">
            <directory>Core/</directory>
        </testsuite>
        <testsuite name="spot">
            <directory>Spot/</directory>
        </testsuite>
        <testsuite name="derivatives">
            <directory>Derivatives/</directory>
        </testsuite>
        <testsuite name="websockets">
            <directory>WebSockets/</directory>
        </testsuite>
    </testsuites>
</phpunit>
' > phpunit.xml;


docker compose up --build -d;

docker compose exec -it php composer install;

docker compose exec -it php composer exec phpunit