#!/usr/bin/env bash
VERSION=`cat plugins/theme-atomio.json | jq -r '.version'`
NAME=myaac-theme-atomio-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
