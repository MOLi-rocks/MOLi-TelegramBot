<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\MOLiBotApiToken;

class MOLiBotApiTokenRepository
{
    public function createToken($token, $user)
    {
        return MOLiBotApiToken::create([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function getAllToken($columns = ['*'])
    {
        return MOLiBotApiToken::all($columns);
    }

    public function checkTokenExist($token)
    {
        return MOLiBotApiToken::where('token', '=', $token)->exists();
    }

    public function deleteToken($token)
    {
        return MOLiBotApiToken::where('token', '=', $token)->delete();
    }
}