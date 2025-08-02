# sample-data

Generator of sample data - generate fake accounts, players & online players, maybe in future something more.

## Commands included
* sample-data:accounts - accounts generator
* sample-data:players - players generator
* sample-data:random-online - online players randomizer

## Usage
```
cd /var/www/html # or any other folder where myaac located is
```

### sample-data:accounts

Generate 10 accounts with default password, which is: **pass1234**
```
php aac sample-data:accounts 10
```

Generate 5 accounts with custom password:
```
php aac sample-data:accounts --password="asdasd" 5
```

### sample-data:players

Generate 500 players with default settings:
```
php aac sample-data:players 500
```

Generate 100 players with 1000 level:
```
php aac sample-data:players --level=1000 500
```

### sample-data:random-online

Add 100 random online players
```
php aac sample-data:random-online 100
```

## Other options

### sample-data:accounts
* --country (country code)
  * example: --country="de" (will be set for all accounts)
  * leaving it empty, will generate random country for every account

### sample-data:players
* --account=x (account id)
* --level=x
* --vocation=x (id of vocation, from 0 to 10 (with monk))
* --town=x (id of town)
* --look-type=x (id of outfit/lookType)
* --look-color=x (color of whole outfit (simplified), number from 0 to 132)
* --look-addons=x (0, 1 or 2)
