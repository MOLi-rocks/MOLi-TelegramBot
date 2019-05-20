<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\LINENotifyUser;

class LINENotifyUserRepository
{
    public function createToken($token)
    {
        LINENotifyUser::create([
            'access_token' => $token
        ]);
    }

    public function getAllToken()
    {
        return LINENotifyUser::where('status', '!=', '401')
            ->pluck('access_token')->toArray();
    }

    public function getTotalStats()
    {
        $total = LINENotifyUser::all()->count();

        $active = LINENotifyUser::where('status', '=', 200)->count();

        $inactive = LINENotifyUser::where('status', '=', 401)->count();

        $others = LINENotifyUser::whereNotIn('status', [200, 401])->count();

        return [
            'Total' => $total,
            'Active' => $active,
            'Inactive' => $inactive,
            'Others' => $others
        ];
    }

    public function getUserStats()
    {
        $total = LINENotifyUser::all()->count();

        $active = LINENotifyUser::where('targetType', 'USER')
            ->where('status', '=', 200)->count();

        $inactive = LINENotifyUser::where('targetType', 'USER')
            ->where('status', '=', 401)->count();

        $others = LINENotifyUser::where('targetType', 'USER')
            ->whereNotIn('status', [200, 401])->count();

        return [
            'Total' => $total,
            'Active' => $active,
            'Inactive' => $inactive,
            'Others' => $others
        ];
    }

    public function getGroupStats()
    {
        $total = LINENotifyUser::all()->count();

        $active = LINENotifyUser::where('targetType', 'GROUP')
            ->where('status', '=', 200)->count();

        $inactive = LINENotifyUser::where('targetType', 'GROUP')
            ->where('status', '=', 401)->count();

        $others = LINENotifyUser::where('targetType', 'GROUP')
            ->whereNotIn('status', [200, 401])->count();

        return [
            'Total' => $total,
            'Active' => $active,
            'Inactive' => $inactive,
            'Others' => $others
        ];
    }

    public function updateUserData($token, $result)
    {
        LINENotifyUser::where('access_token', $token)
            ->update([
                'targetType' => $result['targetType'],
                'target' => $result['target'],
                'status' => $result['status']
            ]);
    }

    public function updateUserStatus($token, $status)
    {
        LINENotifyUser::where('access_token', $token)
            ->update([
                'status' => $status
            ]);
    }
}