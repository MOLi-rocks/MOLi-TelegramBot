<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;
use MOLiBot\Http\Controllers\LINENotifyController;


class LINE_Notify_User extends Model
{
    protected $table = 'line_notify_users';

    protected $fillable = ['access_token', 'targetType', 'target', 'sid', 'email'];

    public static function getAllToken()
    {
        return static::all()
            ->where('status', '!=', '401')
            ->pluck('access_token')
            ->toArray();
    }

    public static function getStats()
    {
        return [
            "total" => static::all()->count(),
            "USER" => static::all()->where("targetType", "USER")->count(),
            "GROUP" => static::all()->where("targetType", "GROUP")->count(),
        ];
    }

    public static function updateStatus($token)
    {
        $result = LINENotifyController::getStatus($token);
        if (is_array($result)) {
            LINE_Notify_User::where('access_token', $token)
                ->update([
                    'targetType' => $result['targetType'],
                    'target' => $result['target'],
                    'status' => $result['status']
                ]);
        } else {
            LINE_Notify_User::where('access_token', $token)
                ->update([
                    'status' => $result
                ]);
        }

        return True;
    }
}
