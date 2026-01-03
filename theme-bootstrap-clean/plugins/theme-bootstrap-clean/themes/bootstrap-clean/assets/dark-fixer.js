// temporary solution for account/lost, commands, polls, gifts and forum board
const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)").matches;
const currentTheme = localStorage.getItem('bsTheme') || (prefersDarkScheme ? 'dark' : 'light');

if (currentTheme === 'dark') {
	$body = $("body");
	$body.find(`[bgcolor='#ffffff']`).attr('bgcolor', '');
	$body.find(`[bgcolor='#F2F2F2']`).attr('bgcolor', '');
}

$(".myaac-table").addClass("table table-striped");
$(".myaac-table").removeClass("myaac-table");
