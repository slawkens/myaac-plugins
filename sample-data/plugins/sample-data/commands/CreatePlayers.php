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
			->addOption('account', null, InputOption::VALUE_OPTIONAL, 'Account ID, by default random')
			->addOption('level', null, InputOption::VALUE_OPTIONAL, 'Level, by default random')
			->addOption('vocation', null, InputOption::VALUE_OPTIONAL, 'Vocation, by default random')
			->addOption('town', null, InputOption::VALUE_OPTIONAL, 'Town, by default 1')
			->addOption('look-type', null, InputOption::VALUE_OPTIONAL, 'lookType, by default random')
			->addOption('look-colors', null, InputOption::VALUE_OPTIONAL, 'lookColors, by default random')
			->addOption('look-addons', null, InputOption::VALUE_OPTIONAL, 'lookAddons, by default random');
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

		$accountId = $input->getOption('account');
		$level = $input->getOption('level');
		$vocation = $input->getOption('vocation');
		$town = $input->getOption('town');

		$lookType = $input->getOption('look-type');
		$lookColors = $input->getOption('look-colors');
		$lookAddons = $input->getOption('look-addons');

		if (!isset($accountId)) {
			$accountIds = [];
			$accountIdsSelect = Account::all(['id'])->toArray();
			foreach ($accountIdsSelect as $row) {
				$accountIds[] = $row['id'];
			}
		}

		$faker = Factory::create();

		for ($i = 0; $i < $amount; $i++) {
			$player = new Player();

			do {
				$firstName = $faker->firstName();
				$lastName = $faker->lastName();
				$fullName = $firstName . ' ' . $lastName;
			}
			while (Player::where('name', $fullName)->exists());

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

			$player->lookhead = $lookColors ?? 0;
			$player->lookbody = $lookColors ?? 0;
			$player->looklegs = $lookColors ?? 0;
			$player->lookfeet = $lookColors ?? 0;

			$player->lookaddons = $lookAddons ?? 0;

			$player->conditions = '';

			$player->save();
		}

		$io->success("Successfully created $amount players.");
		return Command::SUCCESS;
	}
};
