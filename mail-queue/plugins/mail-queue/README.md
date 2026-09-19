# mail-queue

Install, configure Mail options in settings, ready.

This script will send the emails in background. This will eliminate lags caused by sending mails live. Instead they will be "queued" in db, and sent in background.

## Installation

The plugin will work on myaac version v1.9.1 and higher.
If you are using older version, then it's enough to patch this PR manually: https://github.com/slawkens/myaac/pull/368/changes
Also change the version in mail-queue.json accordingly to install the plugin.

## Usage
Use the included run.sh script to run it continously in screen.

`cd plugins/mail-queue`
`screen -dmS mailer ./run.sh`

## Options
* -v (Verbose)
* -q (Quiet)
* -f (Re-run failed emails)
