#!/usr/bin/env bash
VERSION=`cat plugins/dashboard-insights.json | jq -r '.version'`
NAME=myaac-dashboard-insights-v$VERSION.zip
rm -f $NAME
zip -r $NAME plugins/ -x */\.*
