#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/mail-queue.json)
NAME=myaac-mail-queue-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
