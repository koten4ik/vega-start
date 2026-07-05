<?php

namespace Modules\UserProfile\Commands;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UpdateProfileCommand
{
    public function execute($request): bool
    {
        $user = Auth::user();

        $user->name = $request->validated()['name'];
        $user->login = $request->validated()['login'];
        $user->email = $request->validated()['email'];
        $user->profile_phone = $request->validated()['profile_phone'] ?? null;

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
