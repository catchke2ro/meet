<?php


use app\models\Module;
use ReCaptcha\ReCaptcha;

/**
 * @param string $url
 *
 * @return string
 */
function menuActiveClass(string $url): string {
	return preg_match('/^' . preg_quote($url, '/') . '/i', Yii::$app->request->url) ? 'active' : '';
}


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
 * @param int $step
 *
 * @return int
 */
function getIntervalMultiplier(int $step): int {
	return match ($step) {
		12, 6, 3 => $step,
		default => 1,
	};
}


/**
 * @param int $step
 *
 * @return int
 */
function getIntervalStep(int $step): int {
	return match ($step) {
		12, 6, 3 => 1,
		default => $step,
	};
}


/**
 * @param int $threshold
 * @param int $step
 *
 * @return float|int
 */
function getIntervalThreshold(int $threshold, int $step): float|int {
	return match ($step) {
		12, 6, 3 => $threshold / $step,
		default => $threshold,
	};
}


/**
 * @param int $step
 *
 * @return string
 */
function getIntervalName(int $step): string {
	return match ($step) {
		12 => 'év',
		6 => 'félév',
		3 => 'negyedév',
		default => 'hónap',
	};
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


/**
 * Generates slug from the string with a character map
 *
 * @param string      $string      Input string
 * @param string|null $lang        Language code for character map
 * @param bool        $onlyLetters If true, special characters won't be replaced to '-'
 *
 * @return string
 */
function slug(string $string = '', ?string $lang = null, bool $onlyLetters = false): string {
	$slugArray = include __DIR__ . '/slugcharmap.php';

	$slug = mb_strtolower(strip_tags(trim($string))); //Convert to lower case

	//Special replace strings
	$spaceRpl = $onlyLetters ? '' : '-';
	$hyphenRpl = $onlyLetters ? '' : '-';

	//Replace spaces
	$slug = preg_replace('/[ \/\.\,]/', $spaceRpl, $slug);

	//Get array map
	$lang = 'hu';
	$trArray = ($lang && !empty($slugArray['languages'][$lang])) ? array_replace($slugArray['default'], $slugArray['languages'][$lang]) : $slugArray['default'];

	//Replace characters
	$slug = strtr($slug, $trArray);

	$trUnicodeArray = $slugArray['unicode'] ?? [];
	foreach ($trUnicodeArray as $replacement => $chars) {
		$chars = array_map(function ($code) {
			return '\x{' . $code . '}';
		}, $chars);
		$slug = preg_replace('/[' . implode('', $chars) . ']/', $replacement, $slug);
	}

	$slug = preg_replace('/[^a-z0-9\-_]/', '', $slug); //Replace special characters
	$slug = preg_replace('/[\-]+/', $hyphenRpl, $slug);
	$slug = preg_replace('/\-$/', '', $slug);

	return $slug;
}


/**
 * @param string $response
 *
 * @return bool
 */
function recaptchaValidator(string $response): bool {
	$secretKey = Yii::$app->params['recaptcha_secret_key'] ?? null;

	$recaptcha = new ReCaptcha($secretKey);
	$resp = $recaptcha->setExpectedHostname($_SERVER['HTTP_HOST'])
		->verify($response, $_SERVER['REMOTE_ADDR']);

	return $resp->isSuccess();
}

/**
 * @param Module $module
 * @param string $pattern
 *
 * @return string|null
 */
function getLinkForCI(Module $module, string $pattern): ?string {
	$rootDir = Yii::$app->getBasePath() . '/storage/ci';
	$moduleDir = sprintf('meet_modul_%s', $module->slug);
	$file = sprintf($pattern, $module->slug);
	if (file_exists($rootDir . '/' . $moduleDir . '/' . $file)) {
		return '/_ci-download?module=' . $module->id . '&file=' . urlencode($moduleDir . '/' . $file);
	}

	return null;
}