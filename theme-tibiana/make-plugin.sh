#!/usr/bin/env bash
VERSION=`cat plugins/theme-tibiana.json | jq -r '.version'`
NAME=myaac-theme-tibiana-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
