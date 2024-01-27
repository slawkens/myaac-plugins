#!/usr/bin/env bash
VERSION=`cat plugins/theme-coffee-n-cream.json | jq -r '.version'`
NAME=myaac-theme-coffee-n-cream-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
