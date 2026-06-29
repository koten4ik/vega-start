<?php
/**
 * Main Helper
 */

namespace Modules\ZSupport\App\Helpers;

//todo разбить на разные хелперы
use Illuminate\Support\Facades\Crypt;
use Mews\Purifier\Facades\Purifier;

class H
{

	public static function sanitizeText($text)
	{
		//$text = htmlspecialchars($text, ENT_QUOTES);
		//$text = htmlentities($text, ENT_QUOTES);

		//strip_tags
		/*$allow_tags = '<p><\/p><b><\/b><strong><\/strong><i><\/i><b><\/b><a><\/a><ul><\/ul><ol><\/ol><li><\/li><br>';
		$allow_tags .= '<span><\/span><em><\/em><s><\/s><u><\/u>';
		$text = strip_tags($text, $allow_tags);*/

		$config = [
			'HTML.Allowed' => 'p,b,strong,i,em,a[href],ul,ol,li,br,s,u',
		];
		$text = Purifier::clean($text, $config);

		//url process
		$text = str_replace('http:', 'https:', $text);

		return $text;
	}

	public static function csvPrepare($str, $end_row = false)
	{
		$str = str_replace(array("\r\n", "\r", "\n", ";"), '', $str);
		$str = iconv("utf-8", "windows-1251", $str);
		return $str . ($end_row ? "\r\n" : ';');
	}

	public static function generateCSV($data, $filename)
	{
		header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
		header("Content-Disposition: attachment; filename=\"" . $filename . ".csv\"");
		echo $data;
	}

	public static function mtimeFix($f_path)
	{
		if (is_file($_SERVER['DOCUMENT_ROOT'] . '/' . $f_path)) {
			$m_time = filemtime($_SERVER['DOCUMENT_ROOT'] . '/' . $f_path);
			return $f_path . '?' . $m_time;
		}
	}

	public static function cnt($names, $cnt)
	{
		//$names - минута|минуты|минут
		return \Lang::choice($names, $cnt, [], 'ru');
	}

	//<link rel="stylesheet" href="https://cdn.plyr.io/3.6.3/plyr.css"/>
	//<script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
	/*public static function plyrPlayerVideo($id,$url,$params=[]){

		$provider = '';
		if (strpos($url, 'youtu') !== false) $provider = 'youtube';
		if (strpos($url, 'vimeo') !== false) $provider = 'vimeo';
		?>
		<div class="plyr__video-embed" id="<?=$id?>" data-plyr-provider="<?=$provider?>"
			 data-plyr-embed-id="<?=$url?>"></div>
		<script type="text/javascript">
			document.addEventListener("DOMContentLoaded", function () {
				<?=$id?> = new Plyr('#<?=$id?>', {
					//controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'airplay', 'fullscreen'],
					controls: ['play','progress', 'fullscreen', 'volume', 'settings'], //hideControls: true,
					currentTime: 0,	autoplay: false, fullscreen: {enabled: true}
				});
			});
		</script>
		<?
	}*/

	// на странице генерим <div id="{{$block_id}}" class="youtube_frame" data-id="{{$block_id}}" data-url="{{$youtube_url}}"></div>
	// вызываем ф-цию в конце страницы
	public static function youtubeScript()
	{
		?>
		<script>
			var tag = document.createElement('script');
			tag.src = "https://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

			function onYouTubeIframeAPIReady() {
				var player = [100];
				var cnt = 0;
				$('.youtube_frame').each(function (t) {
					console.log($(this).data('id'))
					player = new YT.Player($(this).data('id'), {
						videoId: youtube_parser($(this).data('url')),
					});
					cnt++;
				})
			}

			function youtube_parser(url) {
				var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
				var match = url.match(regExp);
				return (match && match[7].length == 11) ? match[7] : false;
			}
		</script>
		<?php
	}

	public static function getVieoPreview($url)
	{
		$rez = 'https://img.youtube.com/vi/' . self::youtubeID($url) . '/maxresdefault.jpg';

		if (strpos($url, 'vimeo')) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_USERAGENT, 'IE20');
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			$out = curl_exec($curl);
			curl_close($curl);
			preg_match_all('~twitter:image.+(https?://[^\\"]+)~', $out, $twitterImagesArray);
			$arr = end($twitterImagesArray);
			//dd($arr);
			$rez = $arr[0] ?? '';
		}

		return $rez;
	}

	public static function youtubeID($url)
	{
		parse_str(parse_url($url, PHP_URL_QUERY), $vars);
		if (isset($vars['v']) == true)
			$ytID = $vars['v'];
		else {
			$arr = explode('/', $url);
			$ytID = end($arr);

			$arr = explode('#', $ytID);
			$ytID = $arr[0];
			$arr = explode('?', $ytID);
			$ytID = $arr[0];
		}

		return $ytID;
	}


	public static function get_param($url, $name)
	{
		$vars = [];
		parse_str(parse_url($url, PHP_URL_QUERY), $vars);
		return isset($vars[$name]) ? $vars[$name] : null;
	}

	public static function getPostContent($url, $vals = array())
	{
		$opts = array('http' => array('method' => 'POST', 'content' => http_build_query($vals),
			'header' => 'Content-type: application/x-www-form-urlencoded'));
		$context = stream_context_create($opts);
		return file_get_contents($url, false, $context);
	}

	public static function curl($url, $data, $headers = null)
	{
		if ($headers == null)
			$headers = [
				'Content-Type: application/json',
			];

		$data_json = json_encode($data);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);

		$result = curl_exec($curl);
		curl_close($curl);

		$result = json_decode($result, true);

		return $result;
	}


	public static function setCookie($name, $val, $time = 0)
	{
		if ($time == 0) $time = time() + 3600 * 24 * 365;
		setcookie($name, $val, $time, "/");
	}

	public static function getCookie($name)
	{
		return $_COOKIE[$name] ?? null;
	}

	public static function setHttpOnlyCookie($name, $val, $time = 0)
	{
		$cookie = cookie(
			$name,
			Crypt::encrypt($val),
			$time,     // срок жизни в минутах
			'/',    // path
			null,   // домен
			Env::isProd() ? true : false,  // secure
			true,   // HttpOnly
			false,  // raw
			'Lax'   // SameSite
		);

		return $cookie;
	}
}


