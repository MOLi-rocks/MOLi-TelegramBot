<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\User;

class UserRepository
{
    private $userModel;

    /**
     * UserRepository constructor.
     * @param User $userModel
     */
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
}