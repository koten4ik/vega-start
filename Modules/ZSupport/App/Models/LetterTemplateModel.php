<?php

namespace Modules\ZSupport\App\Models;

use Modules\ZSupport\App\Models\VegaModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LetterTemplateModel extends VegaModel
{
	protected $table = 'letter_templates';

    use HasFactory;
    protected $fillable = [
		'name',
		'template',
		'h1',
		'sender',
		'sender_name',
		'receiver',
		'description',
		'subject',
		'body',
    ];

}
