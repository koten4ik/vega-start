<?php

namespace Modules\ZSupport\App\Services\Image;


use Modules\ZSupport\App\Services\File\FileService;
use Modules\ZSupport\App\Services\Logger\Log;
use Intervention\Image\ImageManager;
use Spatie\Image\Image;

class ImageService
{

	public static function imageByModel($model, $field)
	{
		if (!$model || $model->{$field} === null || $model->{$field} === '') return "";

		$file = $model->{$field};
		$file_path = FileService::pathByModel($model) . '/' . $field;
		$img_path = FileService::pathImageByModel($model) . '/' . $field;
		//конвертим оригинал в webp если файл не в webp
		$file = self::toWebp($file, $file_path);
		$url = $img_path . '/' . $file;

		return $url;
	}

	public static function toWebp($file, $source_path)
	{
		$source_img = public_path() . '/' . $source_path . '/' . $file;
		$pathinfo = pathinfo($source_img);
		$file_name = $pathinfo['filename'];
		$source_ext = $pathinfo['extension'] ?? '';
		$new_file = $file_name . '.webp';
		$dest_img = public_path() . '/' . $source_path . '/' . $new_file;

		if ($source_ext === 'webp' || $source_ext === 'gif'
			|| $source_ext === 'heic' || $source_ext === 'svg') {
			return $file;
		}
		if (file_exists($source_img) === false) {
			return $file;
		}
		if (file_exists($dest_img) === true) {
			return $new_file;
		}

		//если болшие фотки имажик на сервере не тянет по памяти
		/*$max_width = 20000;	$max_height = 20000;
		$image_size = self::imageSize($source_img);
		if ($image_size['width'] > $max_width || $image_size['height'] > $max_height) {
			Log::errorCritical('Imagick - '.$source_img);
			return $file;
		} else*/
		{
			$image = new \Imagick($source_img);

			//если надо экономно по месту на диске
			//$max_width = 5000; $max_height = 3000;
			//$image_size = self::imageSize($source_img);
			//if($image_size['width'] > $max_width || $image_size['height'] > $max_height)
			//	$image->thumbnailImage(5000, 3000, true);
			//$image->setImageCompressionQuality(99);//99 - высокое качество, 90 - среднее

			$image->setImageFormat('webp');
			$image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);
			$image->writeImage($dest_img);
		}

		return $new_file;
	}

	public static function imageSize($img)
	{
		$image_info = @getimagesize($img);
		$width = $image_info[0] ?? 0;
		$height = $image_info[1] ?? 0;

		return [
			'width' => $width,
			'height' => $height,
		];
	}

	public static function imageAspect($img)
	{
		$aspect_ratio = 0;
		$size = self::imageSize($img);

		if ($size['width'] > 0 && $size['height'] > 0) {
			$aspect_ratio = $size['width'] / $size['height'];
		}
		return round($aspect_ratio, 2);
	}

	public static function imageAspectName($img)
	{
		return self::imageAspect($img) > 1 ? 'horizontal' : 'vertical';
	}

}
