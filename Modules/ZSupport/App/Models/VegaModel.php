<?php

namespace Modules\ZSupport\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VegaModel extends Model
{

    public $abc_module = 'empty';

    protected $fillable = [
    ];

	public static function tableName(): string
	{
		return (new static())->getTable();
	}

    public function scopeDisplay(Builder $query): Builder
    {
        return $query->where('display', 1);
    }

    public function scopeOrderDef(Builder $query): Builder
    {
        return $query->orderBy('rank', 'desc')->orderBy('id', 'desc');
    }
}
