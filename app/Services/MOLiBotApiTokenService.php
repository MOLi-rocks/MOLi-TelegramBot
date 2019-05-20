<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\MOLiBotApiTokenRepository;

class MOLiBotApiTokenService
{
    private $MOLiBotApiTokenRepository;

    public function __construct(MOLiBotApiTokenRepository $MOLiBotApiTokenRepository)
    {
        $this->MOLiBotApiTokenRepository = $MOLiBotApiTokenRepository;
    }

    public function generateToken()
    {
        return md5(((float) date ( "YmdHis" ) + rand(100,999)).rand(1000,9999));
    }

    public function listToken()
    {
        return $this->MOLiBotApiTokenRepository->getAllToken(['user', 'token', 'created_at'])->toArray();
    }

    public function createToken($user)
    {
        $token = $this->generateToken();

        return $this->MOLiBotApiTokenRepository->createToken($token, $user);
    }

    public function checkTokenExist($token)
    {
        return $this->MOLiBotApiTokenRepository->checkTokenExist($token);
    }

    public function deleteToken($token)
    {
        return $this->MOLiBotApiTokenRepository->deleteToken($token);
    }
}