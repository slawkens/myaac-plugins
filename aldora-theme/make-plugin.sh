#!/usr/bin/env bash
VERSION=`cat plugins/aldora-theme.json | jq -r '.version'`
NAME=myaac-aldora-theme-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
