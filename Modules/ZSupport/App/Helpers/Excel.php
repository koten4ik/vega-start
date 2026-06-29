<?php


namespace Modules\ZSupport\App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

//composer require phpoffice/phpspreadsheet

class Excel
{
    public static function build(array $tabsData, array $headerRow, string $filename)
    {
        // 1. Создаем документ Excel
        $spreadsheet = new Spreadsheet();

        $isFirstTab = true;

        // Проходим циклом по всем вкладкам
        foreach ($tabsData as $tabName => $data) {

            // Для первой итерации используем автоматически созданный лист,
            // для последующих — создаем новый лист в документе
            if ($isFirstTab) {
                $sheet = $spreadsheet->getActiveSheet();
                $isFirstTab = false;
            } else {
                $sheet = $spreadsheet->createSheet();
            }

            // Задаем имя текущей вкладки (например, "Бонус 3")
            $sheet->setTitle($tabName);

            // Добавляем шапку в начало данных этой вкладки
            array_unshift($data, $headerRow);

            // Наполняем лист данными
            $sheet->fromArray($data, null, 'A1');

            // Делаем шапку жирной на текущем листе
            $lastColumnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($headerRow));
            $sheet->getStyle('A1:' . $lastColumnLetter . '1')->getFont()->setBold(true);

            // Автоматическое выравнивание ширины колонок под текст
            foreach (range('A', 'C') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        // Сбрасываем указатель активности на самую первую вкладку,
        // чтобы при открытии файла пользователь видел её первой
        $spreadsheet->setActiveSheetIndex(0);

        // 2. Формируем безопасный StreamedResponse для Laravel (без изменений)
        $response = new StreamedResponse(function () use ($spreadsheet) {
            if (ob_get_length() > 0) {
                ob_end_clean();
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        // 3. Добавляем правильные заголовки (без изменений)
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

}
