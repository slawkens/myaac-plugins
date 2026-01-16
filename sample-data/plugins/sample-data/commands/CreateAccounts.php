<?php

namespace MyAAC\Commands;

use Faker\Factory;
use MyAAC\Models\Account;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

return new class extends Command
{
	protected function configure(): void
	{
		$this->setName('sample-data:accounts')
			->setDescription('Generate random accounts data')
			->addArgument('amount',
				InputArgument::REQUIRED,
				'Amount of accounts to generate'
			)
			->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'Password to use. Default: pass1234')
			->addOption('country', 'c', InputOption::VALUE_OPTIONAL, 'Country to set, in shortcode. Example: pl for Poland. More can be found in system/countries.conf.php');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		global $config;

		require_once __DIR__ . '/../vendor/autoload.php';
		require SYSTEM . 'countries.conf.php';
		require SYSTEM . 'init.php';

		$io = new SymfonyStyle($input, $output);

		$amount = (int)$input->getArgument('amount');
		if (!$amount || $amount < 1) {
			$io->warning('Amount needs to be 1 or higher');
			return Command::FAILURE;
		}

		$password = trim($input->getOption('password') ?? '');
		if (empty($password)) {
			$password = 'pass1234';
		}

		$country = strtolower(trim($input->getOption('country') ?? ''));
		if (empty($country)) {
			$country = 'random';
		}

		$configCountries = config('countries');
		if(!empty($country) && $country != 'random' && !isset($configCountries[$country])) {
			$io->error("Country $country doesn't exist");
			return Command::FAILURE;
		}

		$faker = Factory::create();

		$firstId = $lastId = 'Error';

		for ($i = 0; $i < $amount; $i++) {
			$account = new Account();

			$firstName = $faker->firstName();
			$lastName = $faker->lastName();

			$account->rlname = $firstName . ' ' . $lastName;

			if (USE_ACCOUNT_NAME) {
				$account->name = strtolower($firstName . '_' . $lastName);
			}
			else {
				do {
					$id = rand(1, 99999999);
				}
				while (Account::where('id', $id)->exists());

				if (USE_ACCOUNT_NUMBER) {
					$account->number = $id;
				}
				else {
					$account->id = $id;
				}
			}

			$account->email = $faker->email();
			$account->email_verified = 1;

			$account->country = ($country == 'random' ? array_rand($configCountries) : $country);

			$passwordSalted = $password;
			if (USE_ACCOUNT_SALT) {
				$salt = generateRandomString(10, false, true, true);
				$passwordSalted = $salt . $password;
				$account->salt = $salt;
			}

			$account->created = time();
			$account->password = encrypt($passwordSalted);
			$account->save();

			if ($i == 0) {
				$firstId = $account->id;
			}

			$lastId = $account->id;
		}

		$success = ["Successfully created $amount accounts."];
		if (USE_ACCOUNT_NAME) {
			$success[] = "Generated account first id: $firstId and last id: $lastId.";
		}

		$io->success($success);
		return Command::SUCCESS;
	}
};
