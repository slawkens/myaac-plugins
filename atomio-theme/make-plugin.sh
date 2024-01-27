#!/usr/bin/env bash
VERSION=`cat plugins/atomio-theme.json | jq -r '.version'`
NAME=myaac-atomio-theme-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
