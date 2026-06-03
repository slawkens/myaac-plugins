<?php

namespace MyAAC\Plugins\MailQueue\Models;

use Illuminate\Database\Eloquent\Model;

class MailQueue extends Model
{
	protected $table = 'myaac_mail_queue';

	protected $fillable = [
		'subject', 'body',
		'recipient',
		'priority', 'status',
		'account_id', 'ip',
	];

	const PRIORITY_LOW = 0;
	const PRIORITY_NORMAL = 1;
	const PRIORITY_HIGH = 2;

	const STATUS_PENDING = 0;
	const STATUS_SENT = 1;
	const STATUS_FAILED = 2;
}
