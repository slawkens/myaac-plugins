<?php

namespace MyAAC\Commands;

use Faker\Factory;
use MyAAC\Models\Account;
use MyAAC\Models\Player;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

return new class extends Command
{
	protected function configure(): void
	{
		$this->setName('sample-data:players')
			->setDescription('Generate random players data')
			->addArgument('amount',
				InputArgument::REQUIRED,
				'Amount of players to generate'
			)

			->addOption('file', null, InputOption::VALUE_OPTIONAL, 'File with names used by generator. One name per line.')

			->addOption('account', null, InputOption::VALUE_OPTIONAL, 'Account ID, by default random')
			->addOption('account-from', null, InputOption::VALUE_OPTIONAL, 'First account ID to use, by default disabled')
			->addOption('account-to', null, InputOption::VALUE_OPTIONAL, 'Last account ID to use, by default disabled')

			->addOption('level', null, InputOption::VALUE_OPTIONAL, 'Level, by default random')
			->addOption('vocation', null, InputOption::VALUE_OPTIONAL, 'Vocation, by default random. As number from 0-8')
			->addOption('town', null, InputOption::VALUE_OPTIONAL, 'Town, by default 1')

			->addOption('look-type', null, InputOption::VALUE_OPTIONAL, 'lookType, by default 136 for female, 128 for male')
			->addOption('look-colors', null, InputOption::VALUE_OPTIONAL, 'lookColors, by default random')
			->addOption('look-addons', null, InputOption::VALUE_OPTIONAL, 'lookAddons, by default 0 (zero/none')

			->addOption('look-head', null, InputOption::VALUE_OPTIONAL, 'lookhead, by default random')
			->addOption('look-body', null, InputOption::VALUE_OPTIONAL, 'lookbody, by default random')
			->addOption('look-legs', null, InputOption::VALUE_OPTIONAL, 'looklegs, by default random')
			->addOption('look-feet', null, InputOption::VALUE_OPTIONAL, 'lookfeet, by default random');
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		require_once __DIR__ . '/../vendor/autoload.php';
		require_once __DIR__ . '/../functions.php';
		require SYSTEM . 'init.php';

		$io = new SymfonyStyle($input, $output);

		$amount = (int)$input->getArgument('amount');
		if (!$amount || $amount < 1) {
			$io->warning('Amount needs to be 1 or higher');
			return Command::FAILURE;
		}

		$file = $input->getOption('file');
		$accountId = $input->getOption('account');
		$accountFrom = $input->getOption('account-from');
		$accountTo = $input->getOption('account-to');

		$level = $input->getOption('level');
		$vocation = $input->getOption('vocation');
		$town = $input->getOption('town');

		$lookType = $input->getOption('look-type');
		$lookColors = $input->getOption('look-colors');

		$lookHead = $input->getOption('look-head');
		$lookBody = $input->getOption('look-body');
		$lookLegs = $input->getOption('look-legs');
		$lookFeet = $input->getOption('look-feet');

		$lookAddons = $input->getOption('look-addons');

		$importFromFile = false;

		if ($file) {
			if (!file_exists($file)) {
				$io->error('File not found: ' . $file);
				return Command::FAILURE;
			}

			$countNames = 0;
			$namesFromFile = file($file);
			foreach ($namesFromFile as &$name) {
				$name = trim($name);

				if (strlen($name) > 1) {
					$countNames++;
				}
			}

			if ($amount > $countNames) {
				$io->error("Not enough player names in the file. Needed: $amount, provided: $countNames.");
				return Command::FAILURE;
			}

			$importFromFile = true;
		}

		if (!isset($accountId)) {
			$accountIds = [];
			if ($accountFrom > 0 && $accountTo > $accountFrom) {
				$accountIdsSelect = Account::where('id', '>=', $accountFrom)->where('id', '<=', $accountTo)->get(['id'])->toArray();
			}
			else {
				$accountIdsSelect = Account::all(['id'])->toArray();
			}

			foreach ($accountIdsSelect as $row) {
				$accountIds[] = $row['id'];
			}
		}
		else {
			if (!Account::where('id', $accountId)->exists()) {
				$io->error("Account with id: $accountId not found.");
				return Command::FAILURE;
			}
		}

		$faker = Factory::create();

		$skipped = 0;

		for ($i = 0; $i < $amount; $i++) {
			$player = new Player();

			if ($importFromFile) {
				$fullName = $namesFromFile[$i];

				if (Player::where('name', $fullName)->exists()) {
					$skipped++;
					continue;
				}
			}
			else {
				do {
					$firstName = $faker->firstName();
					$lastName = $faker->lastName();
					$fullName = $firstName . ' ' . $lastName;
				}
				while (Player::where('name', $fullName)->exists());
			}

			$player->account_id = $accountId ?? $accountIds[array_rand($accountIds)];

			$player->name = $fullName;
			$player->vocation = $vocation ?? random_int(0, 8);
			$player->town_id = $town ?? 1;

			$player->level = $level ?? ($player->vocation == VOCATION_NONE ? random_int(0, 8) : random_int(1, 2000));
			$player->experience = getExperienceForLevel($player->level);

			$player->health = getHealthPointsForLevel($player->vocation, $player->level);
			$player->healthmax = getHealthPointsForLevel($player->vocation, $player->level);

			$player->mana = getManaPointsForLevel($player->vocation, $player->level);
			$player->manamax = getManaPointsForLevel($player->vocation, $player->level);

			$player->cap = getCapacityForLevel($player->vocation, $player->level);

			$player->sex = random_int(0, 1);
			$player->looktype = $lookType ?? ($player->sex == 0 ? 136 : 128);

			$player->lookhead = $lookColors ?? $lookHead ?? 0;
			$player->lookbody = $lookColors ?? $lookBody ?? 0;
			$player->looklegs = $lookColors ?? $lookLegs ?? 0;
			$player->lookfeet = $lookColors ?? $lookFeet ?? 0;

			$player->lookaddons = $lookAddons ?? 0;

			$player->conditions = '';

			$player->created = time();

			$player->save();
		}

		$skippedText = '';
		if ($skipped > 0) {
			$amount = $amount - $skipped;
			$skippedText = " Skipped $skipped players (duplicated names in file).";
		}

		$io->success("Successfully created $amount players.$skippedText");
		return Command::SUCCESS;
	}
};
