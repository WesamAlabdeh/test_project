<?php

namespace App\Service;

use App\Models\User;

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
}
