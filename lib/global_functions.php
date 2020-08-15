<?php


/**
 * Print months with bigger units
 *
 * @param int $months
 *
 * @return string|null
 */
function reduceMonths(int $months): ?string {
	foreach (
		[
			12 => 'év',
			6  => 'félév',
			3  => 'negyedév',
			1  => 'hónap'
		] as $divider => $name
	) {
		if (($months % $divider) === 0) {
			return sprintf('%d ' . $name, $months / $divider);
		}
	}

	return null;
}

/**
 * @param $step
 *
 * @return int
 */
function getIntervalMultiplier($step): int {
	switch ($step) {
		case 12:
		case 6:
		case 3:
			return $step;
		default:
			return 1;
	}
}

/**
 * @param int $step
 *
 * @return int
 */
function getIntervalStep(int $step): int {
	switch ($step) {
		case 12:
		case 6:
		case 3:
			return 1;
		default:
			return $step;
	}
}

/**
 * @param int $threshold
 * @param int $step
 *
 * @return float|int
 */
function getIntervalThreshold(int $threshold, int $step) {
	switch ($step) {
		case 12:
		case 6:
		case 3:
			return $threshold / $step;
		default:
			return $threshold;
	}
}

/**
 * @param $step
 *
 * @return string
 */
function getIntervalName(int $step): string {
	switch ($step) {
		case 12:
			return 'év';
		case 6:
			return 'félév';
		case 3:
			return 'negyedév';
		default:
			return 'hónap';
	}
}

/**
 * @param int|null $value
 * @param int      $step
 *
 * @return int
 */
function convertIntervalValue(?int $value, int $step): int {
	if (!$value) {
		return $value;
	}

	return round($value / $step);
}