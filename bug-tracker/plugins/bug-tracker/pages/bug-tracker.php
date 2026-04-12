<?php
/**
 * Bug tracker
 *
 * @package   MyAAC
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @copyright 2026 MyAAC
 * @link      https://my-aac.org
 */

require_once __DIR__ . '/../src/Models/BugTracker.php';

use MyAAC\Plugins\BugTracker\BugTracker;

defined('MYAAC') || die('Direct access not allowed!');
$title = 'Bug tracker';

csrfProtect();

if(!$logged) {
	echo  'You are not logged in. <a href="' . getLink('account/manage') . '&redirect=' . BASE_URL . urlencode
('bug-tracker') . '">Log in</a> to post on the bug tracker.<br /><br />';
	return;
}

$showed = $post = $reply = false;
// type (1 = question; 2 = answer)
// status (1 = open; 2 = new message; 3 = closed;)

$dark = config('darkborder');
$light = config('lightborder');

$tags = [1 => '[MAP]', '[WEBSITE]', '[CLIENT]', '[MONSTER]', '[NPC]', '[OTHER]'];

if (!empty($_SERVER['QUERY_STRING'])) {
	$link = 'bug-tracker';

	if (!empty($_REQUEST['control']) && $_REQUEST['control'] == 'true' && isset($_REQUEST['id'])) {
		$link = 'bug-tracker?control=true';
	}

	echo '<br/><a href="' . getLink($link) . '"><b>Back</b></a><br/><br/>';
}

if(admin() && isset($_REQUEST['control']) && $_REQUEST['control'] == 'true')
{
	if (empty($_REQUEST['id']) || !is_numeric($_REQUEST['id']) ) {
		$bug[1] = BugTracker::where('type', 1)->get()->toArray();
	}

	if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
		$bug[2] = BugTracker::where('type', 1)->where('uid', $_REQUEST['id'])->first()->toArray();
	}

	if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
		$reply = !empty($_REQUEST['reply']);

		$currentBug = BugTracker::where('uid', $_REQUEST['id'])->first();

		if (!$currentBug) {
			$twig->display('error_box.html.twig', ['errors' => ['Issue not found.']]);
			return;
		}

		$account = new OTS_Account();
		$account->load($currentBug->account_id ?? 0);

		if (!$account->isLoaded()) {
			$twig->display('error_box.html.twig', ['errors' => ['Fatal error: Account not found']]);
			return;
		}

		$players = $account->getPlayersList();

		if (!$reply) {
			if ($bug[2]['status'] == 2) {
				$value = '<span style="color: green">[OPEN]</span>';
			}
			elseif($bug[2]['status'] == 3) {
				$value = '<span style="color: red">[CLOSED]</span>';
			}
			elseif($bug[2]['status'] == 1) {
				$value = '<span style="color: blue">[NEW ANSWER]</span>';
			}

			echo '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Bug Tracker</B></TD></TR>';
			echo '<TR BGCOLOR="'.$dark.'"><td width=40%><i><b>Subject</b></i></td><td>'.$tags[$bug[2]['tag']].' '
				.escapeHtml($bug[2]['subject']).' '.$value.'</td></tr>';
			echo '<TR BGCOLOR="'.$light.'"><td><i><b>Posted by</b></i></td><td>';

			foreach($players as $player) {
				echo $player->getName().'<br>';
			}

			echo '</td></tr>';
			echo '<TR BGCOLOR="'.$dark.'"><td colspan=2><i><b>Description</b></i></td></tr>';
			echo '<TR BGCOLOR="'.$light.'"><td colspan=2>'.nl2br(escapeHtml($bug[2]['text'] ?? '')).'</td></tr>';
			echo '</TABLE>';

			$answers = BugTracker::where('uid', $_REQUEST['id'])->where('type', 2)->orderBy('reply')->get()->toArray();

			foreach ($answers as $answer) {
				if ($answer['who'] == 1) {
					$who = '<span style="color: red">[ADMIN]</span>';
				}
				else {
					$who = '<span style="color: green">[PLAYER]</span>';
				}

				echo '<br><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Answer #'.$answer['reply'].'</B></TD></TR>';
				echo '<TR BGCOLOR="'.$dark.'"><td width=70%><i><b>Posted by</b></i></td><td>'.$who.'</td></tr>';
				echo '<TR BGCOLOR="'.$light.'"><td colspan=2><i><b>Description</b></i></td></tr>';
				echo '<TR BGCOLOR="'.$dark.'"><td colspan=2>'.nl2br(escapeHtml($answer['text'])).'</td></tr>';
				echo '</TABLE>';
			}

			if ($bug[2]['status'] != 3) {
				echo '<br><a href="' . getLink('bug-tracker') . '?control=true&id=' . $_REQUEST['id'] . '&reply=true"><b>[REPLY]</b></a>';
			}
		}
		else {
			if ($bug[2]['status'] != 3) {
				$reply = BugTracker::where('uid', $_REQUEST['id'])->where('type', 2)->max('reply');
				$reply++;

				if (isset($_POST['finish'])) {
					if (empty($_POST['text'])) {
						$errors[] = 'Description cannot be empty.';
					}
					if (empty($_POST['status'])) {
						$errors[] = 'Status cannot be empty.';
					}

					if (!empty($errors)) {
						$twig->display('error_box.html.twig', ['errors' => $errors]);
					}
					else {
						$type = 2;

						BugTracker::create([
							'uid' => $_REQUEST['id'],
							'account_id' => $account_logged->getId(),
							'text' => $_POST['text'],
							'reply' => $reply,
							'type' => $type,
							'who' => 1,
						]);

						Bugtracker::where('uid', $_REQUEST['id'])->update([
							'status' => $_POST['status']
						]);

						header('Location: ' . getLink('bug-tracker') . '?control=true&id=' . $_REQUEST['id']);
					}
				}

				echo '<br><form method="post">' . csrf(true) . '<table><tr><td><i>Description</i></td><td><textarea name="text" rows="15" cols="35">' . escapeHtml($_POST['text'] ?? '') . '</textarea></td></tr><tr><td>Status[OPEN]</td><td><input type=radio name=status value=2></td></tr><tr><td>Status[CLOSED]</td><td><input type=radio name=status value=3></td></tr></table><br><input type="submit" name="finish" value="Submit" class="input2"/></form>';
			}
			else {
				echo '<br><span style="color: black"><b>You can\'t add answer to closed bug thread.</b></span>';
			}
		}

		$post = true;
	}

	if (!$post) {
		echo '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['vdarkborder'].'><TD colspan=2 CLASS=white><B>Bug Tracker Admin</B></TD></TR>';

		$i = 1;
		foreach ($bug[1] as $report) {
			if ($report['status'] == 2) {
				$value = '<span style="color: green">[OPEN]</span>';
			}
			elseif ($report['status'] == 3) {
				$value = '<span style="color: red">[CLOSED]</span>';
			}
			elseif ($report['status'] == 1) {
				$value = '<span style="color: blue">[NEW ANSWER]</span>';
			}

			echo '<TR BGCOLOR="' . getStyle($i++) . '"><td width=75%><a href="' . getLink('bug-tracker') . '?control=true&id='.$report['uid'] . '">'.$tags[$report['tag']].' ' . escapeHtml($report['subject']).'</a></td><td>' . $value . '</td></tr>';

			$showed = true;
		}
		echo '</table>';
	}
}
else {
	$acc = $account_logged->getId();

	$allow = count($account_logged->getPlayersList()) > 0;

	if (!empty($_REQUEST['id'])) {
		$id = $_REQUEST['id'];
	}

	if (empty($_REQUEST['id'])) {
		$bug[1] = BugTracker::where('account_id', $account_logged->getId())->where('type', 1)->orderBy('uid')->get()
			->toArray();
	}

	if (!empty($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
		$bug[2] = BugTracker::where('account_id', $account_logged->getId())->where('type', 1)->where('uid', $id)->first();
		if ($bug[2]) {
			$bug[2] = $bug[2]->toArray();
		}
	}
	else {
		$bug[2] = null;
	}

	if (!empty($_REQUEST['id']) && $bug[2] != null) {
		$reply = !empty($_REQUEST['reply']);

		if (!$reply) {
			$value = '';

			if ($bug[2]['status'] == 1) {
				$value = '<span style="color: green">[OPEN]</span>';
			} elseif ($bug[2]['status'] == 2) {
				$value = '<span style="color: blue">[NEW ANSWER]</span>';
			} elseif ($bug[2]['status'] == 3) {
				$value = '<span style="color: red">[CLOSED]</span>';
			}

			echo '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Bug Tracker</B></TD></TR>';
			echo '<TR BGCOLOR="'.$dark.'"><td width=40%><i><b>Subject</b></i></td><td>'.$tags[$bug[2]['tag']].' '.escapeHtml($bug[2]['subject']).' '.$value.'</td></tr>';
			echo '<TR BGCOLOR="'.$light.'"><td colspan=2><i><b>Description</b></i></td></tr>';
			echo '<TR BGCOLOR="'.$dark.'"><td colspan=2>'.nl2br(escapeHtml($bug[2]['text'])).'</td></tr>';
			echo '</TABLE>';

			$answers = Bugtracker::where('uid', $id)->where('type', 2)->orderBy('reply')->get()->toArray();

			foreach ($answers as $answer) {
				if ($answer['who'] == 1) {
					$who = '<span style="color: red">[ADMIN]</span>';
				}
				else {
					$who = '<span style="color: green">[YOU]</span>';
				}

				echo '<br><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['vdarkborder'].'><TD COLSPAN=2 CLASS=white><B>Answer #'.$answer['reply'].'</B></TD></TR>';
				echo '<TR BGCOLOR="'.$dark.'"><td width=70%><i><b>Posted by</b></i></td><td>'.$who.'</td></tr>';
				echo '<TR BGCOLOR="'.$light.'"><td colspan=2><i><b>Description</b></i></td></tr>';
				echo '<TR BGCOLOR="'.$dark.'"><td colspan=2>'.nl2br(escapeHtml($answer['text'] ?? '')).'</td></tr>';
				echo '</TABLE>';
			}

			if ($bug[2]['status'] != 3) {
				echo '<br><a href="' . getLink('bug-tracker') . '?id=' . $id . '&reply=true"><b>[REPLY]</b></a>';
			}
		}
		else {
			if ($bug[2]['status'] != 3) {
				$reply = BugTracker::where('account_id', $acc)->where('uid', $id)->where('type', 2)->max('reply');
				$reply++;

				if (isset($_POST['finish'])) {
					if (empty($_POST['text'])) {
						$errors[] = 'Description cannot be empty.';
					}

					if (!$allow) {
						$errors[] = "You haven't any characters on account.";
					}

					if (!empty($errors)) {
						$twig->display('error_box.html.twig', ['errors' => $errors]);
					}
					else {
						$type = 2;

						$insert = BugTracker::create([
							'uid' => $id,
							'account_id' => $acc,
							'text' => $_POST['text'],
							'reply' => $reply,
							'type' => $type
						]);

						BugTracker::where('uid', $id)->where('account_id', $acc)->update([
							'status' => 1
						]);

						header('Location: ' . getLink('bug-tracker') . '?id=' . $id);
					}
				}

				echo '<br><form method="post">' . csrf(true) . '<table><tr><td><i>Description</i></td><td><textarea name="text" rows="15" 
				cols="35"></textarea></td></tr></table><br><input type="submit" name="finish" value="Submit" class="input2"/></form>';
			}
			else {
				echo '<br><span style="color: black"><b>You can\'t add answer to closed bug thread.</b></span>';
			}
		}

		$post = true;
	}
	elseif (!empty($_REQUEST['id']) && $bug[2] == null) {
		echo '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.config('vdarkborder').'><TD CLASS=white><B>Bug Tracker</B></TD></TR>';
		echo '<TR BGCOLOR="'.$dark.'"><td><i>Bug doesn\'t exist.</i></td></tr>';
		echo '</TABLE>';
		$post = true;
	}

	if (!$post) {
		if (!isset($_REQUEST['add']) || !$_REQUEST['add']) {
			echo '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR='.$config['vdarkborder'].'><TD colspan=2 CLASS=white><B>Bug Tracker</B></TD></TR>';

			$i = 0;
			foreach ($bug[1] as $report) {
				if ($report['status'] == 1) {
					$value = '<span style="color: green">[OPEN]</span>';
				}
				elseif($report['status'] == 2) {
					$value = '<span style="color: blue">[NEW ANSWER]</span>';
				}
				elseif($report['status'] == 3) {
					$value = '<span style="color: red">[CLOSED]</span>';
				}

				$bgcolor = getStyle($i++);

				echo '<TR BGCOLOR="'.$bgcolor.'"><td width=75%><a href="' . getLink('bug-tracker') . '?id='.$report['uid'].'">'.$tags[$report['tag']].' '.escapeHtml($report['subject']).'</a></td><td>'.$value.'</td></tr>';

				$showed = true;
			}

			if (!$showed) {
				echo '<TR BGCOLOR="'.$dark.'"><td><i>You don\'t have reported any bugs.</i></td></tr>';
			}

			echo '</TABLE>';

			echo '<br><a href="' . getLink('bug-tracker') . '?add=true"><b>[ADD REPORT]</b></a>';
		}
		elseif (isset($_REQUEST['add']) && $_REQUEST['add']) {
			if (isset($_POST['submit'])) {
				if (empty($_POST['subject'])) {
					$errors[] = 'Subject cannot be empty.';
				}
				if (empty($_POST['text'])) {
					$errors[] = 'Description cannot be empty.';
				}
				if (!$allow) {
					$errors[] = "You haven't any characters on account.";
				}
				if (empty($_POST['tags'])) {
					$errors[] = 'Tag cannot be empty.';
				}

				if (!empty($errors)) {
					$twig->display('error_box.html.twig', ['errors' => $errors]);
				}
				else {
					$type = 1;
					$status = 1;

					$uid = BugTracker::max('uid');

					$insert = BugTracker::create([
						'uid' => ++$uid,
						'account_id' => $acc,
						'text' => $_POST['text'],
						'type' => $type,
						'subject' => $_POST['subject'],
						'reply' => 0,
						'status' => $status,
						'tag' => $_POST['tags']
					]);

					header('Location: ' . getLink('bug-tracker') . '?id=' . $uid);
				}
			}

			echo '<br><form method="post">' . csrf(true) . '<table><tr><td><i>Subject</i></td><td><input type=text name="subject" value="' . escapeHtml($_POST['subject'] ?? '') . '"/></td></tr><tr><td><i>Description</i></td><td><textarea name="text" rows="15" cols="35">' . escapeHtml($_POST['text'] ?? '') . '</textarea></td></tr><tr><td>TAG</td><td><select name="tags"><option value="">SELECT</option>';

			for($i = 1; $i <= count($tags); $i++) {
				echo '<option value="' . $i . '">' . $tags[$i] . '</option>';
			}

			echo '</select></tr></tr></table><br><input type="submit" name="submit" value="Submit" class="input2"/></form>';
		}
	}
}

if(admin() && empty($_REQUEST['control'])) {
	echo '<br><br><a href="' . getLink('bug-tracker') . '?control=true">[ADMIN PANEL]</a>';
}
