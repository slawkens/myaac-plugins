#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/spells.json)
NAME=myaac-spells-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
