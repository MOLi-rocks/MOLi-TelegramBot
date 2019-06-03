<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\MOLiBotApiToken;

class MOLiBotApiTokenRepository
{
    private $MOLiBotApiTokenModel;

    /**
     * MOLiBotApiTokenRepository constructor.
     * @param MOLiBotApiToken $MOLiBotApiTokenModel
     */
    public function __construct(MOLiBotApiToken $MOLiBotApiTokenModel)
    {
        $this->MOLiBotApiTokenModel = $MOLiBotApiTokenModel;
    }

    public function createToken($token, $user)
    {
        return $this->MOLiBotApiTokenModel->create([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function getAllToken($columns = ['*'])
    {
        return $this->MOLiBotApiTokenModel->all($columns);
    }

    public function checkTokenExist($token)
    {
        return $this->MOLiBotApiTokenModel
            ->where('token', '=', $token)
            ->exists();
    }

    public function deleteToken($token)
    {
        return $this->MOLiBotApiTokenModel
            ->where('token', '=', $token)
            ->delete();
    }
}