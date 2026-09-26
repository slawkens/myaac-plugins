#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/npcs.json)
NAME=myaac-npcs-v$VERSION.zip
rm -f "$NAME"
zip -r "$NAME" plugins/ -x */\.*
