<?php

namespace Modules\Shop\Queries;

use Modules\Shop\Models\CategoryModel;

class CategoryBySlugQuery
{
    public static function get($slug)
    {
        return CategoryModel::where('slug', $slug);
    }
}
