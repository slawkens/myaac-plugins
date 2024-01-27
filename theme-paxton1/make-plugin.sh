#!/usr/bin/env bash
VERSION=`cat plugins/theme-paxton1.json | jq -r '.version'`
NAME=myaac-theme-paxton1-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
