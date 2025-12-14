# lua-monsters

## What is this?

This is a loader of monsters written in Lua for the Canary engine. (https://github.com/opentibiabr/canary)

## Requirements
* MyAAC version 1.0-RC or higher
* PHP-extension: lua

The PHP Lua extension can be installed on linux (Ubuntu) following this tutorial: https://stackoverflow.com/a/79846908/1055314.

On Windows it's a bit tricky to install the extension, but still possible. I will write one day how to do it. For now following info should be enough: you need to download the correct version of Windows DLLs from https://pecl.php.net/package/lua, paste into php/ext folder of xampp/UniServ, and then enable the lua extension in php.ini

## How to access this page?

Just write /monsters in the browser address.
Example: http://localhost/index.php/monsters

## How to reload?

Access the page, like in previous step, with admin account, and click on the "Reload monsters" button. It may take up to 20 seconds to load all on slow HDD. So be patient. The script execution limit is set to 60 seconds tho, so if you are on slow machine, it may fail to execute.

You will then need to edit the `plugins/lua-monsters/src/Monsters.php` and change following line to a higher number:

`set_time_limit(60);`

Where 60 is time in seconds.

## Monster with item images

Some monsters got outfit defined as lookTypeEx, this is normally item id.

The default gesior item images host doesn't display those images, because they are not pickable. So you will need to set Item Images URL in Admin Panel -> Settings -> Images as follows:
`https://item-images-oracle.ots.me/latest_otbr/`
