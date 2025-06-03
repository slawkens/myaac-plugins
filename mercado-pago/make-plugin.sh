#!/usr/bin/env bash
VERSION=$(jq -r '.version' < plugins/mercado-pago.json)
NAME=myaac-mercado-pago-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
