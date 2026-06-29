<?php

namespace Modules\ZSupport\App\Services;

use Modules\SitePage\Models\PageModel;


class MetaTags
{
	public static $elems = array();

	public static function draw()
	{
		$result = '';

		if (count(self::$elems)) {
			foreach (self::$elems as $name => $content) {
				if ($name == 'title') {
					$result .= '<title>' . $content . '</title>' . "\n";
				} elseif ($name == 'canonical') {
					$result .= '<link rel="canonical" href="' . $content . '">' . "\n";
				} else {
					//todo сделать проверку $name на "og:" и при успехе <meta name= заменять на <meta property=
					$result .= '<meta name="' . $name . '" content="' . htmlspecialchars($content) . '" />' . "\n";
				}
			}
		} else {
			$mainMeta = self::mainMeta();
			$result .= '<title>' . ($mainMeta['title'] ?? '') . '</title>' . "\n";
			$result .= '<meta name="description" content="' . ($mainMeta['description']??'') . '"/>' . "\n";
		}

		return $result;
	}

	public static function mainMeta()
	{
		$result = [];
		$page = PageModel::query()->module('index')->first();
		if ($page) {
			$result['title'] = $page->meta_title;
			$result['description'] = $page->meta_description;
		}
		return $result;
	}

    public static function addFromPageModule($module)
    {
        $page = PageModel::query()->module($module)->first();
        self::addFromPage($page);
    }

    public static function addFromPage($page)
    {
        if ($page) {
            self::$elems['title'] = $page->meta_title;
            self::$elems['description'] = $page->meta_description;
        }
    }

	public static function add($name, $content)
	{
		self::$elems[$name] = str_replace('"', '&quot;', $content);
	}

	public static function addArr($arr)
	{
	    if($arr)
		foreach ($arr as $name => $content) {
			self::add($name, $content);
		}
	}

	/*public static function ogImageUrl()
	{
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			if ($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/5.0 (compatible; vkShare; +http://vk.com/dev/Share)')
				return '/img/og_image_vk.jpg';
			if ($_SERVER['HTTP_USER_AGENT'] == 'TelegramBot (like TwitterBot)')
				return '/img/og_image.jpg';
		}
		return '/img/og_image.jpg';
	}*/
}
