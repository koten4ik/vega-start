<?php

namespace Modules\SitePage\Models;

use Illuminate\Database\Eloquent\Builder;
use Modules\SitePage\Enums\PageDomain;
use Modules\SitePage\Enums\PageModule;
use Modules\ZSupport\App\Models\VegaModel;

class PageModel extends VegaModel
{
    public $table = 'pages';

    protected $fillable = [
        'name',
        'slug',
        'text',
        'domain',
        'module',
        'display',
        'display_menu',
        'display_menu_footer',
        'display_menu_footer2',
        'rank',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'display' => 'boolean',
        'display_menu' => 'boolean',
        'display_menu_footer' => 'boolean',
        'display_menu_footer2' => 'boolean',
    ];

    public function getUrl()
    {
        $url = $this->slag;
        if ($this->module == 'index') $url = '';

        return request()->getSchemeAndHttpHost() . '/' . $url;
    }

    public function scopeModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

}
