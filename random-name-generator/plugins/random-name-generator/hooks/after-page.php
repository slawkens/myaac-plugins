<?php
defined('MYAAC') or die('Direct access not allowed!');

global $action;

// MyAAC 1.0+
if (PAGE !== 'account/characters/create' && PAGE !== 'account/create' &&
	// MyAAC 0.8
	PAGE !== 'createaccount' && (PAGE !== 'accountmanagement' || $action !== 'create_character')) {
	return;
}

global $template_place_holders;
if(!isset($template_place_holders['body_end'])) {
	$template_place_holders['body_end'] = [];
}

ob_start();
?>

<button type="button" id="generate_random_name" style="margin-left: 10px; padding: 2px 8px; cursor: pointer;">Suggest name</button>
<script src="https://cdn.jsdelivr.net/gh/Coldensjo/TibiaNameGen/random_name_generator.js"></script>
<script>
	$(function () {
		$('#generate_random_name').insertAfter('#character_indicator');
	})
</script>

<?php
$template_place_holders['body_end'][] = ob_get_clean();
