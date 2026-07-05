<?php

namespace Modules\UserProfile\Commands;

use Illuminate\Support\Facades\Auth;
use Modules\UserProfile\ViewModels\ProfileViewModel;

class ViewProfileCommand
{
    public function execute()
    {
        return [
            'profile' => ProfileViewModel::data(Auth::user()),
        ];
    }
}
