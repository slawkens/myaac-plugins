# monsters

## What is this?

This is a loader of monsters - both .lua and .xml is supported. For canary (Lua) the PHP Lua extension is required.
In MyAAC 3.0 the monsters and spells have been removed from core, thus this plugin.
In previous versions you don't need it.

## Requirements
* MyAAC version 3.0-dev or higher
* (for canary) PHP-extension: lua

The PHP Lua extension can be installed on linux (Ubuntu) following this tutorial: https://stackoverflow.com/a/79846908/1055314.

On Windows it's a bit tricky to install the extension, but still possible. I will write one day how to do it. For now following info should be enough: you need to download the correct version of Windows DLLs from https://pecl.php.net/package/lua, paste into php/ext folder of xampp/UniServ, and then enable the lua extension in php.ini.

If you get error in php cli, that Lua extension cannot be loaded, then paste also liblua.dll and liblua.pdb from downloaded PECL, into php main folder (where php.exe is located)

## How to access this page?

Just write /monsters in the browser address.
Example: http://localhost/index.php/monsters

## How to reload?

Access the page, like in previous step, with admin account, and click on the "Reload monsters" button. It may take up to 20 seconds to load all on slow HDD. So be patient. The script execution limit is set to 60 seconds tho, so if you are on slow machine, it may fail to execute.

You can also reload monsters in admin panel -> Server Data -> Reload.

## Monster with item images

Some monsters got outfit defined as lookTypeEx, this is normally item id.

The default gesior item images host doesn't display those images, because they are not pickable. So you will need to set Item Images URL in Admin Panel -> Settings -> Images as follows:
`https://item-images-oracle.ots.me/latest_otbr/`

# Configuration
Configuration can be found in admin panel -> Settings -> Monsters.
