<?php

namespace Modules\ZSupport\App\Services;


class Config
{
    private static $_config = null;

    public static function get($name, $default = null)
    {
		try {
			if(self::$_config==null) {
				require_once(public_path().'/abc'.DIRECTORY_SEPARATOR.'_config.php');
				self::$_config = $config;
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
        $value = null;
        if(isset(self::$_config[$name])) $value = self::$_config[$name];

        if (!$value && $default) $value = $default;

        return $value;
    }

    public static function set($name,$value)
    {
        if(self::$_config==null) {
            require_once(public_path().'/abc'.DIRECTORY_SEPARATOR.'_config.php');
            self::$_config = $config;
        }

        $content = "<?php\r\n";
       	foreach(self::$_config as $k=>$v) {
       	    if($k==$name) $v = $value;
            $content .= '$config[\'' . $k . '\']=\'' . str_replace("'", "\'", $v) . '\';' . "\r\n";
        }

       	$fp = fopen(public_path().'/abc'.DIRECTORY_SEPARATOR.'_config.php', 'w');
       	fwrite($fp,$content);
       	fclose($fp);
    }
}
