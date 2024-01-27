#!/usr/bin/env bash
VERSION=`cat plugins/theme-old-school.json | jq -r '.version'`
NAME=myaac-theme-old-school-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.* -x "plugins/theme-old-school/themes/old-school/images/bg.psd"
