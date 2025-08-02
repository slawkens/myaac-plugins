#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/sample-data.json)
NAME=myaac-sample-data-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
