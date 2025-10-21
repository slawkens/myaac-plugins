#!/usr/bin/env bash
VERSION=`cat plugins/password-strength.json | jq -r '.version'`
NAME=myaac-password-strength-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
