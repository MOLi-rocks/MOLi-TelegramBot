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
            'Total' => [
                'Total' => static::all()->count(),
                'Active' => static::all()->where('status', '=', 200)->count(),
                'Inactive' => static::all()->where('status', '=', 401)->count(),
                'Others' => static::all()->whereNotIn('status', [200, 401])->count()
            ],
            'USER' => [
                'Active' => static::all()->where('targetType', 'USER')->where('status', '=', 200)->count(),
                'Inactive' => static::all()->where('targetType', 'USER')->where('status', '=', 401)->count(),
                'Others' => static::all()->where('targetType', 'USER')->whereNotIn('status', [200, 401])->count()
            ],
            'GROUP' => [
                'Active' => static::all()->where('targetType', 'GROUP')->where('status', '=', 200)->count(),
                'Inactive' => static::all()->where('targetType', 'GROUP')->where('status', '=', 401)->count(),
                'Others' => static::all()->where('targetType', 'GROUP')->whereNotIn('status', [200, 401])->count()
            ],
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
