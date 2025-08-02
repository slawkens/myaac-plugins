<?php
const VOCATION_NONE = 0,
VOCATION_FIRST = VOCATION_NONE,
VOCATION_SORCERER = 1,
VOCATION_DRUID = 2,
VOCATION_PALADIN = 3,
VOCATION_KNIGHT = 4,
VOCATION_MASTER_SORCERER = 5,
VOCATION_ELDER_DRUID = 6,
VOCATION_ROYAL_PALADIN = 7,
VOCATION_ELITE_KNIGHT = 8,
VOCATION_MONK = 9,
VOCATION_EXALTED_MONK = 10,
VOCATION_LAST = VOCATION_EXALTED_MONK;

if (!function_exists('getExperienceForLevel')) {
	function getExperienceForLevel($level): float|int {
		return ( 50 / 3 ) * pow( $level, 3 ) - ( 100 * pow( $level, 2 ) ) + ( ( 850 / 3 ) * $level ) - 200;
	}
}

if (!function_exists('getHealthPointsForLevel')) {
	function getHealthPointsForLevel(int $vocation, int $level): int
	{
		if ($level <= 8) {
			return 150 + (($level - 1) * 5);
		}

		// default modifier for sorcerer, druid, none
		$modifier = 5;

		switch ($vocation) {
			case VOCATION_KNIGHT:
			case VOCATION_ELITE_KNIGHT:
				$modifier = 15;
				break;

			case VOCATION_PALADIN:
			case VOCATION_ROYAL_PALADIN:
			case VOCATION_MONK:
			case VOCATION_EXALTED_MONK:
				$modifier = 10;
				break;
		}

		return 185 + ($modifier * ($level - 8));
	}
}

if (!function_exists('getManaPointsForLevel')) {
	function getManaPointsForLevel(int $vocation, int $level): int
	{
		if ($level <= 8) {
			return 55 + (($level - 1) * 5);
		}

		// default modifier for knight, none
		$modifier = 5;

		switch ($vocation) {
			case VOCATION_PALADIN:
			case VOCATION_ROYAL_PALADIN:
				$modifier = 15;
				break;

			case VOCATION_DRUID:
			case VOCATION_ELDER_DRUID:
			case VOCATION_SORCERER:
			case VOCATION_MASTER_SORCERER:
				$modifier = 30;
				break;

			case VOCATION_MONK:
			case VOCATION_EXALTED_MONK:
				$modifier = 10;
				break;
		}

		return 90 + ($modifier * ($level - 8));
	}
}

if (!function_exists('getCapacityForLevel')) {
	function getCapacityForLevel(int $vocation, int $level): int
	{
		if ($level <= 8) {
			return 400 + (($level - 1) * 10);
		}

		// default modifier for druid, sorcerer, none
		$modifier = 10;

		switch ($vocation) {
			case VOCATION_KNIGHT:
			case VOCATION_ELITE_KNIGHT:
			case VOCATION_MONK:
			case VOCATION_EXALTED_MONK:
				$modifier = 25;
				break;

			case VOCATION_PALADIN:
			case VOCATION_ROYAL_PALADIN:
				$modifier = 20;
				break;
		}

		return 470 + ($modifier * ($level - 8));
	}
}
