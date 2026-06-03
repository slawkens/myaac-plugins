<?php

namespace MyAAC\Commands;

use MyAAC\Plugins\MailQueue\Models\MailQueue;
use MyAAC\Plugins\MailQueue\Models\MailQueueHistory;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

return new class extends Command
{
	private PHPMailer $mailer;
	private OutputInterface $output;
	private SymfonyStyle $io;

	protected function configure(): void
	{
		$this->setName('mail-queue:process')
			->setDescription('Process the mail queue')
			->addArgument('amount',
				InputArgument::OPTIONAL,
				'Amount of emails to process'
			)
			->addOption('failed', 'f', InputOption::VALUE_NONE, 'Only process failed emails');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		require SYSTEM . 'init.php';

		$this->output = $output;

		$this->io = new SymfonyStyle($input, $output);

		if (setting('core.mail_enabled') == 0) {
			$this->io->error('Mail sending is disabled in the settings. Please enable it to use this command.');

			return Command::FAILURE;
		}

		$amount = (int) $input->getArgument('amount');
		if (!$amount) {
			$amount = 5; // default amount
		}

		$failed = $input->getOption('failed');

		$this->mailer = new PHPMailer();

		$mailOption = setting('core.mail_option');
		if($mailOption == MAIL_SMTP)
		{
			$this->mailer->isSMTP();
			$this->mailer->Host = setting('core.smtp_host');
			$this->mailer->Port = setting('core.smtp_port');
			$this->mailer->SMTPAuth = setting('core.smtp_auth');
			$this->mailer->Username = setting('core.smtp_user');
			$this->mailer->Password = setting('core.smtp_pass');

			$security = setting('core.smtp_security');

			$tmp = '';
			if ($security === SMTP_SECURITY_SSL) {
				$tmp = 'ssl';
			}
			else if ($security == SMTP_SECURITY_TLS) {
				$tmp = 'tls';
			}

			$this->mailer->SMTPSecure = $tmp;
		}
		else {
			$mailer->isMail();
		}

		$this->mailer->isHTML(true);
		$this->mailer->CharSet = 'utf-8';
		$this->mailer->From = setting('core.mail_address');
		$this->mailer->Sender = setting('core.mail_address');
		$this->mailer->FromName = setting('core.server_name');

		if(setting('core.smtp_debug')) {
			$this->mailer->SMTPDebug = 2;
			$this->mailer->Debugoutput = 'echo';
		}

		$emails = MailQueue::where('status', $failed ? MailQueue::STATUS_FAILED : MailQueue::STATUS_PENDING)
			->orderBy('priority', 'desc')
			->orderBy('id', 'asc')
			->limit($amount)
			->get();

		$i = 0;
		foreach ($emails as $email) {
			$success = $this->processEmail($email);

			if ($this->output->isVerbose() && $success) {
				$this->io->success("Processed email to {$email->recipient} with subject {$email->subject}");
			}

			$i++;
		}

		if (!$this->output->isQuiet()) {
			if ($i > 0) {
				$this->io->success("Successfully processed $i emails.");
			}
			else {
				$this->io->warning('No emails to process.');
			}
		}

		return Command::SUCCESS;
	}

	private function processEmail(MailQueue $email): bool
	{
		$this->mailer->clearAllRecipients();
		$this->mailer->Subject = $email->subject;
		$this->mailer->addAddress($email->recipient);

		$signature_html = setting('core.mail_signature_html');
		$tmp_body = $email->body . '<br/><br/>' . $signature_html;

		$this->mailer->Body = $tmp_body;

		$signature_plain = setting('core.mail_signature_plain');
		if(isset($altBody[0])) {
			$this->mailer->AltBody = $altBody . $signature_plain;
		}
		else { // automatically generate plain html
			$this->mailer->AltBody = strip_tags(preg_replace('/<a(.*)href="([^"]*)"(.*)>/','$2', $email->body)) . "\n" . $signature_plain;
		}

		ob_start();

		if (!$this->mailer->send())
		{
			$error = ob_get_clean();

			if (!$this->output->isQuiet()) {
				$this->io->error("Failed to send email to {$email->recipient} with subject {$email->subject}. Error: {$this->mailer->ErrorInfo} - {$error}");
			}

			log_append('mail-queue-error.log', PHP_EOL . $this->mailer->ErrorInfo . PHP_EOL . $error);
			$email->status = MailQueue::STATUS_FAILED;
			$email->save();

			return false;
		}

		if (!empty($email->ip) || $email->account_id != 0) {
			MailQueueHistory::create([
				'ip' => $email->ip,
				'account_id' => $email->account_id,
			]);
		}

		$email->delete();
		ob_end_clean();

		return true;
	}
};
