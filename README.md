An app that causes a database deadlock
======================================

## Requirements

### Prerequisites

Ubuntu 16.04 and later.

### Install Docker
```
sudo apt-get install docker docker.io docker-compose
```

### To be able to run Docker as a non-root user
https://docs.docker.com/engine/installation/linux/linux-postinstall/#manage-docker-as-a-non-root-user
```
sudo groupadd docker
sudo usermod -aG docker $USER
```
Re-login for user groups to reload.

## Setup

```
docker-compose up -d --build
```

```
docker-compose run deadlock composer install --no-interaction \
&& docker-compose run deadlock bin/console doctrine:migrations:migrate --no-interaction \
&& docker-compose run deadlock bin/console doctrine:fixtures:load --no-interaction
```

## Run

Run
```
docker-compose run deadlock bin/console deadlock a
```

Within 5 seconds in another terminal run
```
docker-compose run deadlock bin/console deadlock b
```
You will get a deadlock in the first process.

## Teardown

```
docker-compose stop db
docker-compose rm
```
