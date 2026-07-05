<?php

namespace Modules\UserProfile\Commands;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateProfileCommand
{
    public function execute($request): bool
    {
        $user = Auth::user();
        $data = $request->validated();

        $user->name = $data['name'];
        $user->login = $data['login'];
        $user->email = $data['email'];
        $user->profile_phone = $data['profile_phone'] ?? null;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return true;
    }
}
