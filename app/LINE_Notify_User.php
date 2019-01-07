<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;


class LINE_Notify_User extends Model
{
    protected $table = 'line_notify_users';

    protected $fillable = ['access_token', 'targetType', 'target', 'sid', 'email'];

    public static function getAllToken()
    {
        return static::all()->pluck('access_token')->toArray();
    }

    public static function getStats()
    {
        return [
            "total" => static::all()->count(),
            "USER" => static::all()->where("targetType", "USER")->count(),
            "GROUP" => static::all()->where("targetType", "GROUP")->count(),
        ];
    }
}
