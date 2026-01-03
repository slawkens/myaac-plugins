#!/usr/bin/env bash
VERSION=`cat plugins/theme-bootstrap-clean.json | jq -r '.version'`
NAME=myaac-theme-bootstrap-clean-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
