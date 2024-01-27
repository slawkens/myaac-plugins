#!/usr/bin/env bash
VERSION=`cat plugins/theme-rasta.json | jq -r '.version'`
NAME=myaac-theme-rasta-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
