An app that causes a database deadlock
======================================

## Setup


```sql
CREATE DATABASE `deadlock` DEFAULT CHARACTER SET utf8;
```

```
composer install --no-interaction
bin/console doctrine:migrations:migrate --no-interaction
bin/console doctrine:fixtures:load --no-interaction
```

## Run

Run in sequence, the second command no longer than 5 seconds after the first.
```
bin/console deadlock a
```
Then 
```
bin/console deadlock b
```
You will get a deadlock in the first process.
