#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/random-name-generator.json)
NAME=myaac-random-name-generator-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
