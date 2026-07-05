<?php

namespace Modules\SitePage\ViewModels;

use Modules\SitePage\Models\PageModel;

class PageViewModel
{
    public static function data(?PageModel $page): array|false
    {
        $data = [];
        if (!$page) return false;

        $data = [
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
        ];

        return $data;
    }
}
