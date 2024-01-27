#!/usr/bin/env bash
VERSION=`cat plugins/theme-darkritual.json | jq -r '.version'`
NAME=myaac-theme-darkritual-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
