<?php

namespace MyAAC\Plugins\MailQueue\Models;

use Illuminate\Database\Eloquent\Model;

class MailQueueHistory extends Model
{
	protected $table = 'myaac_mail_queue_history';

	protected $fillable = [
		'account_id', 'ip',
	];
}
