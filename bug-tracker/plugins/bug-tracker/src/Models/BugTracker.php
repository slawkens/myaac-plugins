<?php

namespace MyAAC\Plugins\BugTracker;

use Illuminate\Database\Eloquent\Model;

class BugTracker extends Model {

	protected $table = TABLE_PREFIX . 'bug_tracker';

	protected $fillable = ['uid', 'account_id', 'type', 'status', 'text', 'subject', 'reply', 'who', 'tag'];

}
