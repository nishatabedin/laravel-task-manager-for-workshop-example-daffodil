<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function getAllUsers()
    {
        return User::orderBy('name')->get();
    }
}
