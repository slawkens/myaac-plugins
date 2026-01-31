<?php
defined('MYAAC') or die('Direct access not allowed!');

if ($logged) {
	return;
}

?>
<div class="well widget loginContainer" id="loginContainer">
	<div class="header">
		Login
	</div>
	<div class="body">
		<form class="loginForm" action="<?= getLink('account/manage') ?>" method="post">

			<?= csrf(true); ?>

			<div class="well">
				<label for="account_login"><?= getAccountLoginByLabel(); ?>:</label>
				<input type="text" name="account_login" id="account_login" required>
			</div>
			<div class="well">
				<label for="password_login">Password:</label>
				<input type="password" name="password_login" id="password_login" required>
			</div>

			<div class="well">
				<input type="checkbox" id="remember_me" name="remember_me" value="true" />
				<label for="remember_me"> Remember me</label>
			</div>

			<div class="well">
				<?php $hooks->trigger(HOOK_ACCOUNT_LOGIN_AFTER_REMEMBER_ME); ?>
			</div>

			<div class="well">
				<input type="submit" value="Login">
			</div>
			<center>
				<h3><a href="<?= getLink('account/create') ?>">Create account</a></h3>
				<p><a href="<?= getLink('account/lost') ?>">Lost account?</a></p>
			</center>
		</form>
	</div>
</div>
