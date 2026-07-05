<?php

namespace Modules\SitePage\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\SitePage\Enums\PageModule;
use Modules\ZSupport\App\Models\VegaModel;

class PageModel extends VegaModel
{
    public $table = 'pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'module',
        'display',
        'display_menu',
        'display_menu_footer',
        'is_indexable',
        'rank',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'display' => 'boolean',
        'display_menu' => 'boolean',
        'display_menu_footer' => 'boolean',
        'is_indexable' => 'boolean',
    ];

    public function getUrl()
    {
        $url = $this->slug;
        if ($this->module == 'index') $url = '';

        return request()->getSchemeAndHttpHost() . '/' . $url;
    }

    public function scopeModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

}
