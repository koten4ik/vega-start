<?php

namespace Modules\UserAccountRegister\Commands;

use Modules\User\Services\UserService;

class RegisterUserCommand
{
    public function __construct(private CreateUserCommand $createUserCommand)
    {
    }

    public function execute($request): bool
    {
        $user = $this->createUserCommand->execute($request->validated());

        UserService::login($user);

        return true;
    }
}
