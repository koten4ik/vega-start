<?php


namespace Modules\ZSupport\App\Services\File;


class UploadService
{
	public function uploadFile($file, $path)
	{
		if (move_uploaded_file($file['tmp_name'], $path) == false){
			return [
				'message' => 'Ошбка загрузки файла или ошибка сервера',
				'description' => ''
			];
		}

		return false;
	}

	public function uploadValidate($file)
	{
		if (!$file) {
			return [
				'message' => 'Нет загруженных файлов.',
				'description' => ''
			];
		}
		if ($file['error'] > 0) {
			return [
				'message' => 'Ошибка загрузки: ' . $file['error'],
				'description' => ''
			];
		}

		return false;
	}

	public function fileSizeValidate($file, $max_file_size)
	{
		if ($max_file_size > 30 || !$max_file_size) $max_file_size = 30;

		if ($file['size'] > $max_file_size * 1024 * 1024) {
			return [
				'message' => 'Превышен размер файла',
				'description' => 'Максимум: ' . $max_file_size . 'Мб'
			];
		}

		return false;
	}

	public function extensionValidate($ext, $extensions)
	{
		$allowedExtensions = ['jpg', 'jpeg', 'heic', 'png', 'gif', 'webp', 'doc', 'docx', 'xls', 'xlsx', 'pdf'];
		if(!$extensions || (isset($extensions[0]) && $extensions[0]=='') )
			$extensions = ['jpg', 'jpeg','png', 'gif', 'webp'];

		$white_list_check = in_array(strtolower($ext), $allowedExtensions);

		if ($white_list_check == false || in_array(strtolower($ext), $extensions) == false) {
			return [
				'message' => 'Неверный формат',
				'description' => 'Доступные форматы: ' . mb_strtoupper(implode(',',$extensions))
			];
		}

		return false;
	}

	public function resolutionValidate($file, $ext, $min_res)
	{
		if ($min_res[0] > 0 && !in_array($ext, ['gif', 'svg']))
		{
			$dims = getimagesize($file['tmp_name']);
			if ($dims && ($dims[0] < $min_res[0] || $dims[1] < $min_res[1]))
			{
				return [
					'message' => 'Изображение слишком маленькое',
					'description' => 'Изображение должно иметь размер не менее ' . $min_res[0] . 'x' . $min_res[1] . '&nbsp;px'
				];
			}
		}

		return false;
	}
}
