#!/usr/bin/env bash
VERSION=`cat plugins/theme-loleslav.json | jq -r '.version'`
NAME=myaac-theme-loleslav-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.* -x "plugins/theme-loleslav/themes/loleslav/sources/bg03.psd"
