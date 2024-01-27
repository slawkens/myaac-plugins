#!/usr/bin/env bash
VERSION=`cat plugins/theme-tibia-old.json | jq -r '.version'`
NAME=myaac-theme-tibia-old-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
