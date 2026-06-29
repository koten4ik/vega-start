<?php

namespace Modules\ZSupport\Domain\ConsoleCommands;


use Modules\Post\Models\PostModel;
use Modules\UserNotification\Entities\UserEvent;

class DeletePostEventCommand
{

	public function execute($params)
	{
		$post = PostModel::find($params['post_id']);
		UserEvent::call_PostDeleted($post);
	}
}
