# Changelog sample-data

## v1.1.1 - 2025-01-16
* Accounts generator:
  * Support for servers without accounts name column
  * Make the --country parameter case-insensitive
  * Set email_verified to 1 by default
* Players generator:
  * Support for servers without players cap, conditions or lookaddons columns
  * Fixed level being set to 0 for non-vocation, which caused command to fail

## v1.1 - 2025-11-04
* Created this changelog
* Added "name" into composer.json, to prevent duplicated class name
* Accounts generator: show first and last id generated
* Players generator:
  * New option: import names from file, with one name per line
  * New options: account-from & account-to (first and last account ids to use, will be randomized)
  * New option to specify exact look colors: --look-head=x, -look-body=x, --look-legs=x, --look-feet=x
  * Added created timestamp to prevent showing "Created: 1970" in characters page

## v1.0 - 2025-08-02
* First release
