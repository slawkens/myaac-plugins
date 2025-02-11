#!/usr/bin/env bash
VERSION=`cat plugins/lua-spells.json | jq -r '.version'`
NAME=myaac-lua-spells-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
