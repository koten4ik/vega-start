<?php


namespace Modules\ZSupport\App\Services\File;


class FileService
{
	const STORAGE_PATH = 'abc/files';
	const TEMP_DIR = 'temp';

	const IMAGE_PATH = '/_imgs/{size}/files';

	private static function modelPath($model)
	{
		return $model->abc_module . '/' . $model->id;
	}

	public static function tempPath()
	{
		return self::STORAGE_PATH . '/' . self::TEMP_DIR;
	}

	public static function tempExist($path)
	{
		if($path === null) return false;

		// Если передан полный путь, проверяем его
		if (str_contains($path, self::tempPath())) {
			return file_exists(public_path($path));
		}

		// Если передан только имя файла, проверяем в temp директории
		$fullPath = public_path(self::tempPath() . '/' . $path);
		return file_exists($fullPath);
	}

	public static function pathByModel($model)
	{
		return self::STORAGE_PATH . '/' . self::modelPath($model);
	}

	public static function fileByModel($model, $field)
	{
		if ($model->{$field} === '' || $model->{$field} === null) return "";

		$file = $model->{$field};
		$path = self::pathByModel($model) . '/' . $field;
		$url = $path . '/' . $file;

		return $url;
	}

	public static function pathImageByModel($model)
	{
		return self::IMAGE_PATH . '/' . self::modelPath($model);
	}

	public static function nameFromPath($path)
	{
		$array = explode("/", $path);
		return end($array);
	}

	public static function pathWithoutName($path)
	{
		$array = explode("/", $path);
		array_pop($array);
		return implode('/',$array);
	}

	public static function moveFromTemp($file_name, $path)
	{
		$result = false;

		//может прилетать полный путь
		$file_name = self::nameFromPath($file_name);

		if ($file_name) {
			$src = self::tempPath() . '/' . $file_name;
			$result = self::copyFile($src, $path);
			if($result) {
				unlink($src);
			}
		}

		return $result;
	}

	public static function copyFile($src, $dest_path)
	{
		$result = false;
		$file_name = self::nameFromPath($src);

		if (file_exists($src) && $file_name) {
			if (!is_dir($dest_path)) mkdir($dest_path, 0755, true);
			$success = copy($src, $dest_path . '/' . $file_name);
			if($success) {
				$result = $file_name;
			}
		}

		return $result;
	}

}
