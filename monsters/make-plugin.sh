#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/monsters.json)
NAME=myaac-monsters-v$VERSION.zip
rm -f "$NAME"
zip -r "$NAME" plugins/ -x */\.*
