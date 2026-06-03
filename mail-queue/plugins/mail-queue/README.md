# mail-queue

Install, configure Mail options in settings, ready.

## Usage
Add following script to the crontab:
`php /var/www/html/aac mail-queue:process 5`

Adjust the path to your aac instance accordingly.

Optionally you can provide a number - how many emails to process, in this example - 5.

You can also specifiy -v (verbose) or -f (re-run failed messages).

This will send the emails in background.
