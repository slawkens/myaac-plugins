#!/usr/bin/env bash
VERSION=`cat plugins/gesior-shop-system.json | jq -r '.version'`
NAME=myaac-gesior-shop-system-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
