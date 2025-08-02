<?php

namespace MyAAC\Commands;

use MyAAC\Models\Player;
use MyAAC\Models\PlayerOnline;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

return new class extends Command
{
	private string $deletedColumn = '';

	protected function configure(): void
	{
		$this->setName('sample-data:random-online')
			->setDescription('Generate random online players')
			->addArgument('amount',
				InputArgument::REQUIRED,
				'Amount of accounts to generate'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		require SYSTEM . 'init.php';
		global $db;

		$io = new SymfonyStyle($input, $output);

		$amount = (int)$input->getArgument('amount');
		if (!$amount || $amount < 1) {
			$io->warning('Amount needs to be 1 or higher');
			return Command::FAILURE;
		}

		if ($db->hasColumn('players', 'deleted')) {
			$this->deletedColumn = 'deleted';
		}
		elseif ($db->hasColumn('players', 'deletion')) {
			$this->deletedColumn = 'deletion';
		}

		$lastId = Player::orderBy('id', 'desc')->first();
		$unique = [];

		$totalCount = Player::where($this->deletedColumn, 0)->count();
		if ($amount > $totalCount) {
			$io->warning("Amount must be less than or equal to $totalCount");
			return Command::FAILURE;
		}

		if ($db->hasColumn('players', 'online')) {
			Player::where('online', '>', 0)->update(['online' => 0]);

			for ($i = 0; $i < $amount; $i++) {
				$id = $this->getExistingPlayerId($lastId->id);

				if (isset($unique[$id])) { // repeat
					$i--;
					continue;
				}

				$player = Player::find($id);
				$player->online = 1;
				$player->save();

				$unique[$id] = true;
			}
		}
		else if ($db->hasTable('players_online')) {
			PlayerOnline::truncate();

			for ($i = 0; $i < $amount; $i++) {
				$id = $this->getExistingPlayerId($lastId->id);

				if (isset($unique[$id])) { // repeat
					$i--;
					continue;
				}

				PlayerOnline::create([
					'player_id' => $id,
				]);

				$unique[$id] = true;
			}
		}
		else {
			$io->error('Unable to generate random online players - no online column or players_online table');
			return Command::FAILURE;
		}

		$io->success("Successfully randomized $amount online players.");
		return Command::SUCCESS;
	}

	private function getExistingPlayerId($lastId): int
	{
		do {
			$id = random_int(1, $lastId);

			$playerQuery = Player::where('id', $id);
			if (!empty($this->deletedColumn)) {
				$playerQuery = $playerQuery->where($this->deletedColumn, 0);
			}
		}
		while (!$playerQuery->exists());

		return $id;
	}
};
