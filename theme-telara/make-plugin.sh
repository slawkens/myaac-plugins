#!/usr/bin/env bash
VERSION=`cat plugins/theme-telara.json | jq -r '.version'`
NAME=myaac-theme-telara-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
