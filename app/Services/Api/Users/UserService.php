<?php

namespace App\Services\Api\Users;

use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    public function store($password, $user): void
    {
        $user->user()->create([
            'first_name'     => $user->first_name,
            'middle_name'    => $user->middle_name,
            'last_name'      => $user->last_name,
            'username'       => $user->username,
            'personal_email' => $user->personal_email,
            'company_email'  => $user->company_email,
            'password'       => Hash::make($password),
            'avatar'         => $user->avatar,
            'is_active'      => $user->is_active,
        ]);
    }
}
