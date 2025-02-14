#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/lua-monsters.json)
NAME=myaac-lua-monsters-v$VERSION.zip
rm -f "$NAME"
zip -r "$NAME" plugins/ -x */\.*
