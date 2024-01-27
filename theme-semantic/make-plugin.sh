#!/usr/bin/env bash
VERSION=`cat plugins/theme-semantic.json | jq -r '.version'`
NAME=myaac-theme-semantic-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
