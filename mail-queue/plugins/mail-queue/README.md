# mail-queue

Install, configure Mail options in settings, ready.

This script will send the emails in background. This will eliminate lags caused by sending mails live. Instead they will be "queued" in db, and sent in background.

## Installation

The plugin will work on myaac version v1.9.1 and higher.
If you are using older version, then it's enough to patch this PR manually: https://github.com/slawkens/myaac/pull/368/changes
Also change the version in mail-queue.json accordingly to install the plugin.

## Usage
Add following script to the crontab:
`php /var/www/html/aac mail-queue:process 5`

Adjust the path to your aac instance accordingly.

Optionally you can provide a number - how many emails to process, in this example - 5.

You can also specifiy -v (verbose) or -f (re-run failed messages).

## Example bash script for linux

You can run this inside of the `screen` command

```bash
#!/bin/bash

while true; do
	php aac mail-queue:process 2>&1 | tee -a system/logs/send_mails.log
	sleep 1
done
```

In this example, 2 emails will be send per second.
