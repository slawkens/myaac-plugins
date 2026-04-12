#!/usr/bin/env bash
VERSION=`cat plugins/bug-tracker.json | jq -r '.version'`
NAME=myaac-bug-tracker-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
