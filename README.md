An app that causes a database deadlock
======================================

## Setup

## Run

Run in sequence, the second command no longer than 5 seconds after the first.
```$xslt
bin/console deadlock a
```
Then 
```$xslt
bin/console deadlock b
```
You will get a deadlock in the first process.
