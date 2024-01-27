#!/usr/bin/env bash
VERSION=`cat plugins/theme-ShadowCores.json | jq -r '.version'`
NAME=myaac-theme-ShadowCores-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
