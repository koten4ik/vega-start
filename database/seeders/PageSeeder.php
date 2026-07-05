<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SitePage\Enums\PageModule;
use Modules\SitePage\Models\PageModel;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        PageModel::updateOrCreate(
            ['slug' => 'contacts'],
            [
                'module' => PageModule::TEXT_PAGE->value,
                'title' => 'Контакты',
                'content' => '<p>Свяжитесь с нами.</p><p>Email: info@vega-start.local</p>',
                'display' => true,
                'display_menu' => true,
                'display_menu_footer' => true,
                'is_indexable' => true,
                'rank' => 0,
                'meta_title' => 'Контакты',
                'meta_description' => 'Контактная информация Vega Start',
            ]
        );
    }
}
