#!/usr/bin/env bash
VERSION=`cat plugins/theme-emma.json | jq -r '.version'`
NAME=myaac-theme-emma-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
