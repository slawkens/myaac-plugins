#!/usr/bin/env bash
VERSION=`cat plugins/theme-trees.json | jq -r '.version'`
NAME=myaac-theme-trees-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
