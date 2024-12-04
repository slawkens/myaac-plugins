<?php
defined('MYAAC') or die('Direct access not allowed!');

require __DIR__ . '/libs/OTS_GuildWars_List.php';
require __DIR__ . '/libs/OTS_Guild_List.php';
require __DIR__ . '/libs/OTS_GuildWar.php';

$hasGuildsBalanceColumn = $db->hasColumn('guilds', 'balance');

$hasGuildWarsNameColumn = $db->hasColumn('guild_wars', 'name1') && $db->hasColumn('guild_wars', 'name2');
$hasGuildWarsStartedColumn = $db->hasColumn('guild_wars', 'started');
$hasGuildWarsEndedColumn = $db->hasColumn('guild_wars', 'ended');

$hasGuildWarsFragLimitColumn = $db->hasColumn('guild_wars', 'frag_limit');
$hasGuildWarsFragsLimitColumn = $db->hasColumn('guild_wars', 'frags_limit');

$hasGuildWarsDeclarationDateColumn = $db->hasColumn('guild_wars', 'declaration_date');
$hasGuildWarsDurationDaysColumn = $db->hasColumn('guild_wars', 'duration_days');

$hasGuildWarsBountyColumn = $db->hasColumn('guild_wars', 'bounty');
$hasGuildWarsPaymentColumn = $db->hasColumn('guild_wars', 'payment');

$canDurationDays = $hasGuildWarsDurationDaysColumn;

$canFragLimit = $hasGuildWarsFragLimitColumn || $hasGuildWarsFragsLimitColumn;
$fragLimitColumn = ($hasGuildWarsFragLimitColumn ? 'frag_limit' : ($hasGuildWarsFragsLimitColumn ? 'frags_limit' : ''));

$canBounty = $hasGuildsBalanceColumn && ($hasGuildWarsBountyColumn || $hasGuildWarsPaymentColumn);
$bountyColumn = ($hasGuildWarsBountyColumn ? 'bounty' : ($hasGuildWarsPaymentColumn ? 'payment' : ''));

$extraQuery = '';
if ($hasGuildWarsNameColumn) {
	$extraQuery = '`guild_wars`.`name1`, `guild_wars`.`name2`, ';
}

if ($hasGuildWarsStartedColumn && $hasGuildWarsEndedColumn) {
	$extraQuery .= '`guild_wars`.`started`, `guild_wars`.`ended`, ';
}
if ($hasGuildWarsDeclarationDateColumn) {
	$extraQuery .= '`guild_wars`.`declaration_date`, ';
}
if ($canDurationDays) {
	$extraQuery .= '`guild_wars`.`duration_days`, ';
}
if ($canBounty) {
	$extraQuery .= '`guild_wars`.`' . $bountyColumn . '`, ';
}
if ($canFragLimit) {
	$extraQuery .= '`guild_wars`.`' . $fragLimitColumn . '`, ';
}

$orderBy = 'started';
if (!$hasGuildWarsStartedColumn && $hasGuildWarsDeclarationDateColumn) {
	$orderBy = 'declaration_date';
}

function displayGuildWars($warsDb, $warFrags, $guild = null, $isLeader = false) {
	global $twig, $hasGuildWarsNameColumn, $logged;

	$wars = [];
	foreach ($warsDb as $war) {
		$war['guildLogoPath1'] = getGuildLogoById($war['guild1']);
		$war['guildLogoPath2'] = getGuildLogoById($war['guild2']);

		if (!$hasGuildWarsNameColumn) {
			$war['name1'] = getGuildNameById($war['guild1']);
			$war['name2'] = getGuildNameById($war['guild2']);
		}

		$wars[] = $war;
	}

	$twig->display('guild-wars/templates/guild_wars.html.twig', [
		'logged' => $logged,
		'isLeader' => $isLeader,
		'guild' => $guild,
		'wars' => $wars,
		'warFrags' => $warFrags,
	]);
}
