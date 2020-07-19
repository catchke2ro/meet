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