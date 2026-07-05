<?php

namespace Modules\UserProfile\Http\Controllers;

use Modules\UserProfile\Commands\UpdateProfileCommand;
use Modules\UserProfile\Commands\ViewProfileCommand;
use Modules\UserProfile\Http\Requests\UpdateProfileRequest;
use Modules\ZSupport\App\Controllers\VegaController;

class ProfileController extends VegaController
{
    public function profilePage(ViewProfileCommand $viewProfileCommand)
    {
        return $this->render($this->getModuleName() . '::profile', $viewProfileCommand->execute());
    }

    public function update(UpdateProfileRequest $request, UpdateProfileCommand $updateProfileCommand)
    {
        if ($updateProfileCommand->execute($request))
            return redirect(route('user.cabinet') . '?saved');
    }
}
