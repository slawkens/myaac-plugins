#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/lua-spells.json)
NAME=myaac-lua-spells-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
