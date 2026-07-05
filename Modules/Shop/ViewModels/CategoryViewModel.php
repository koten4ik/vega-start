<?php

namespace Modules\Shop\ViewModels;

use Modules\Shop\Models\CategoryModel;

class CategoryViewModel
{
    public static function data(?CategoryModel $category): array|false
    {
        $data = [];
        if (!$category) return false;

        $data = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
        ];

        return $data;
    }
}
