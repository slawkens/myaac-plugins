#!/usr/bin/env bash
VERSION=`cat plugins/theme-emberfall.json | jq -r '.version'`
NAME=myaac-theme-emberfall-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
