#!/usr/bin/env bash
VERSION=`cat plugins/theme-reborn.json | jq -r '.version'`
NAME=myaac-theme-reborn-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
