<h3>Top 5 players</h3>
<?php

$players = getTopPlayers(5);

if ($players) {
	$count = 1;
	foreach($players as $player) {
		$link = getLink('characters') . '/' . $player['name'];
		echo "$count - <a href='{$link}'>". $player['name']. "</a> (". $player['level'] .").<br>";
		$count++;
	}
}
