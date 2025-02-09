<div class="sidebar">
	<h2>Top 5 players</h2>
	<?php

	$players = getTopPlayers(5);
	if ($players) {
		foreach($players as $player) {
			$link = getLink('characters') . '/' . urlencode($player['name']);
			echo $player['rank'] . '- ' . '<a href="' . $link . '">' . $player['name'] . '</a>' . ' (' . $player['level'] . ').<br/>';
		}
	}
	?>
</div>
