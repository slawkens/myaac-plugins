<?php
defined('MYAAC') or die('Direct access not allowed!');

use MyAAC\Plugins\MailQueue\Models\MailQueue;
use MyAAC\Plugins\MailQueue\Models\MailQueueHistory;

global $logged, $account_logged;
/*
 * This hook will be called when MyAAC tries to send an email. We will intercept it and add it to the queue instead.
 * The email will be sent later by a cron job, which will call the send.php script in this plugin.
 */

/** @var array $args */

$isMassMail = defined('MYAAC_ADMIN') && empty($_POST['mail_to']);
$priority = $isMassMail ? MailQueue::PRIORITY_LOW : MailQueue::PRIORITY_HIGH;

if ($isMassMail) {
	$accountId = 0;
	$ip = '';
}
else {
	if (defined('MYAAC_ADMIN')) {
		$accountId = 0;
	}
	else {
		$accountId = $logged ? $account_logged->getId() : 0;
	}

	$ip = get_browser_real_ip();
}

if ($accountId != 0) {
	$dailyMails = MailQueueHistory::where('account_id', $accountId)->where('created_at', '>', date("Y-m-d H:i:s", time() - 24 * 60 * 60))->count();
	$hourMails = MailQueueHistory::where('account_id', $accountId)->where('created_at', '>', date("Y-m-d H:i:s", time() - 60 * 60))->count();
	$minuteMails = MailQueueHistory::where('account_id', $accountId)->where('created_at', '>', date("Y-m-d H:i:s", time() - 60))->count();

	//var_dump('Account', $dailyMails, $hourMails, $minuteMails);
	if ($dailyMails >= 100) {
		warning('You have reached the daily email limit. Please try again later.');
		$args['return'] = false;
		return;
	}
	if ($hourMails >= 20) {
		warning('You have reached the hourly email limit. Please try again later.');
		$args['return'] = false;
		return;
	}
	if ($minuteMails >= 5) {
		warning('You have reached the minutely email limit. Please try again later.');
		$args['return'] = false;
		return;
	}
}

if (!empty($ip)) {
	$dailyMails = MailQueueHistory::where('ip', $ip)->where('created_at', '>', date("Y-m-d H:i:s", time() - 24 * 60 * 60))->count();
	$hourMails = MailQueueHistory::where('ip', $ip)->where('created_at', '>', date("Y-m-d H:i:s", time() - 60 * 60))->count();
	$minuteMails = MailQueueHistory::where('ip', $ip)->where('created_at', '>', date("Y-m-d H:i:s", time() - 60))->count();

	//var_dump('IP', $dailyMails, $hourMails, $minuteMails);
	if ($dailyMails >= 100) {
		warning('You have reached the daily email limit. Please try again later.');
		$args['return'] = false;
		return;
	}
	if ($hourMails >= 20) {
		warning('You have reached the hourly email limit. Please try again later.');
		$args['return'] = false;
		return;
	}
	if ($minuteMails >= 5) {
		warning('You have reached the minutely email limit. Please try again later.');
		$args['return'] = false;
		return;
	}
}

MailQueue::create([
	'recipient' => $args['recipient'],
	'subject' => $args['subject'],
	'body' => $args['body'],
	'priority' => $priority,
	'ip' => $ip,
	'account_id' => $accountId,
]);

$args['return'] = true;
