#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/gesior-shop-system.json)
NAME=myaac-gesior-shop-system-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
