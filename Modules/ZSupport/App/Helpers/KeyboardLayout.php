<?php

namespace Modules\ZSupport\App\Helpers;

class KeyboardLayout
{
	protected static array $ru = [
		'й','ц','у','к','е','н','г','ш','щ','з','х','ъ',
		'ф','ы','в','а','п','р','о','л','д','ж','э',
		'я','ч','с','м','и','т','ь','б','ю',
		'Й','Ц','У','К','Е','Н','Г','Ш','Щ','З','Х','Ъ',
		'Ф','Ы','В','А','П','Р','О','Л','Д','Ж','Э',
		'Я','Ч','С','М','И','Т','Ь','Б','Ю',
	];

	protected static array $en = [
		'q','w','e','r','t','y','u','i','o','p','[',']',
		'a','s','d','f','g','h','j','k','l',';','\'',
		'z','x','c','v','b','n','m',',','.',
		'Q','W','E','R','T','Y','U','I','O','P','{','}',
		'A','S','D','F','G','H','J','K','L',':','"',
		'Z','X','C','V','B','N','M','<','>',
	];

	protected static array $map = [];

	protected static function initMap(): void
	{
		if (!empty(self::$map)) {
			return;
		}

		$length = min(count(self::$ru), count(self::$en));
		for ($i = 0; $i < $length; $i++) {
			self::$map[self::$ru[$i]] = self::$en[$i];
			self::$map[self::$en[$i]] = self::$ru[$i];
		}
	}

	public static function switchLayout(string $text): string
	{
		self::initMap();
		return strtr($text, self::$map);
	}
}



