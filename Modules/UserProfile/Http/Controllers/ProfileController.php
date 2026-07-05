<?php

namespace Modules\UserProfile\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Modules\UserProfile\Commands\UpdateProfileCommand;
use Modules\UserProfile\Http\Requests\UpdateProfileRequest;
use Modules\ZSupport\App\Controllers\VegaController;

class ProfileController extends VegaController
{
    public function profilePage()
    {
        return $this->render($this->getModuleName() . '::profile', [
            'user' => Auth::user(),
        ]);
    }

    public function update(UpdateProfileRequest $request, UpdateProfileCommand $updateProfileCommand)
    {
        if ($updateProfileCommand->execute($request))
            return redirect(route('user.cabinet') . '?saved');
    }
}
