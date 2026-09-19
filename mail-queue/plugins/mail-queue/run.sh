#!/bin/bash

# Run this script to continuously process the mail queue
# first give rights: chmod +x run.sh
# then run: screen -dmS mailer ./run.sh
# in this example, the script will process 2 emails at a time

cd ../../

while true; do
	php aac mail-queue:process 2>&1 | tee -a system/logs/send_mails.log
	sleep 1
done
