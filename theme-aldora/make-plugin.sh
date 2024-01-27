#!/usr/bin/env bash
VERSION=`cat plugins/theme-aldora.json | jq -r '.version'`
NAME=myaac-theme-aldora-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
