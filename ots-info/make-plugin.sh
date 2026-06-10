#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/ots-info.json)
NAME=myaac-ots-info-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
