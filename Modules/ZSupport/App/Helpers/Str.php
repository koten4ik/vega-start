<?php

namespace Modules\ZSupport\App\Helpers;

class Str
{

	static function translitIt($source, $clean = true)
	{
		//return str_replace(" ", "-", transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()',$source) );

		$replaceList = array(
			"А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I", "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N",
			"О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH", "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "",
			"Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l",
			"м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "",
			"ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", " " => "-", 'ё' => 'jo', 'Ё' => 'JO',);
		$cleanList = array('`&([a-z]+)(acute|grave|circ|cedil|tilde|uml|lig|ring|caron|slash);`i' => '\1', '`&(amp;)?[^;]+;`i' => '-', '`[^a-z0-9]`i' => '-', '`[-]+`' => '-',);
		$source = str_replace(array_keys($replaceList), array_values($replaceList), $source);
		$source = htmlentities($source, ENT_COMPAT, 'UTF-8');
		if ($clean) $source = preg_replace(array_keys($cleanList), array_values($cleanList), $source);
		$source = strtolower(trim($source, '-'));
		return $source;
	}

	public static function shortText($text, $max_chars = 250)
	{
		$text = strip_tags($text);
		if (iconv_strlen($text) > $max_chars)
			$text = mb_substr($text, 0, $max_chars - 4, 'UTF-8') . '...';
		return $text;
	}

	public static function toW1251($str)
	{
		return iconv("utf-8", "windows-1251", $str);
	}

	public static function toUtf8($str)
	{
		return iconv("windows-1251", "utf-8", $str);
	}

	public static function nl2_br($str)
	{
		return str_replace(array("\r\n", "\r", "\n"), '<br>', $str);
	}

	public static function checkBlackList($str)
	{
		$rez = true;
		$stop_list = explode(', ',self::t('common|word_stop_list'));
		$name = explode(' ', $str);

		foreach($stop_list as $elem)
		{
			foreach($name as $part){
				if(strcmp(mb_strtolower($part),mb_strtolower($elem)) === 0){
					$rez = false;
				}
			}
		}

		return $rez;
	}

	public static function t($str_raw){
		global $lang;
		$str = explode('|',$str_raw);
		$rezult = $lang[ $str[0] ][ $str[1] ] ?? $str_raw;
		if(isset($_GET['i18n'])) $rezult = $str_raw;
		return $rezult;
	}

	public static function rawLinkToTagA(string $text): string
	{
		// Разделяем текст по уже существующим тегам <a>...</a>, чтобы их не трогать
		$parts = preg_split('/(<a\b[^>]*>.*?<\/a>)/is', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

		foreach ($parts as &$part) {
			// Пропускаем уже готовые теги
			if (preg_match('/^<a\b[^>]*>.*<\/a>$/is', $part)) {
				continue;
			}
			// Ищем только сырые ссылки (http/https/ftp)
			$pattern = '/\b((?:https?|ftp):\/\/[^\s<]+)/i';
			$part = preg_replace_callback($pattern, function ($matches) {
				$url = $matches[1];
				// Видимый текст без протокола
				$display = str_replace(['http://','https://','www.'],'',$url);
				// Экранируем
				$safeHref = htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
				$safeText = htmlspecialchars($display, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

				return '<a href="' . $safeHref . '" target="_self" rel="noopener noreferrer">' . $safeText . '</a>';
			}, $part);
		}

		unset($part);
		// Склеиваем обратно
		return implode('', $parts);
	}

	//сервис на питоне по языковой модели
	public static function textToVector($text) {
		//pip install sentence-transformers
		//pip install flask sentence-transformers torch
		//запуск сервиса - python3 vector_to_text_service.py
		//nohup python3 vector_to_text_service.py > vector_to_text_service.log 2>&1 &

		$url = 'http://127.0.0.1:5000/embed';
		$data = json_encode(['text' => $text]);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		curl_close($ch);

		$json = json_decode($response, true);
		if(isset($json['embedding'])) {
			$vector = $json['embedding'];
			// Нормализация вектора
			$norm = sqrt(array_sum(array_map(fn($v) => $v * $v, $vector)));
			if($norm > 0) {
				$vector = array_map(fn($v) => $v / $norm, $vector);
			}

			return $vector;
		}

		return null;
	}
}


