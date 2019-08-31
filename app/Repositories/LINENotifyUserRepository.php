<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\LINENotifyUser;

class LINENotifyUserRepository
{
    private $LINENotifyUserModel;

    /**
     * LINENotifyUserRepository constructor.
     * @param LINENotifyUser $LINENotifyUserModel
     */
    public function __construct(LINENotifyUser $LINENotifyUserModel)
    {
        $this->LINENotifyUserModel = $LINENotifyUserModel;
    }

    public function createToken($token)
    {
        $this->LINENotifyUserModel->create([
            'access_token' => $token
        ]);
    }

    public function getAllToken()
    {
        return $this->LINENotifyUserModel->pluck('access_token');
    }

    public function getSendMsgToken()
    {
        return $this->LINENotifyUserModel
            ->where('status', '!=', '401')
            ->pluck('access_token');
    }

    public function getTotalStats()
    {
        $total = $this->LINENotifyUserModel->all()->count();

        $active = $this->LINENotifyUserModel
            ->where('status', '=', 200)
            ->count();

        $inactive = $this->LINENotifyUserModel
            ->where('status', '=', 401)
            ->count();

        $others = $this->LINENotifyUserModel
            ->whereNotIn('status', [200, 401])
            ->count();

        return [
            'Total' => $total,
            'Active' => $active,
            'Inactive' => $inactive,
            'Others' => $others
        ];
    }

    public function getUserStats()
    {
        $total = $this->LINENotifyUserModel->all()->count();

        $active = $this->LINENotifyUserModel
            ->where('targetType', 'USER')
            ->where('status', '=', 200)->count();

        $inactive = $this->LINENotifyUserModel
            ->where('targetType', 'USER')
            ->where('status', '=', 401)->count();

        $others = $this->LINENotifyUserModel
            ->where('targetType', 'USER')
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
        $total = $this->LINENotifyUserModel->all()->count();

        $active = $this->LINENotifyUserModel
            ->where('targetType', 'GROUP')
            ->where('status', '=', 200)->count();

        $inactive = $this->LINENotifyUserModel
            ->where('targetType', 'GROUP')
            ->where('status', '=', 401)->count();

        $others = $this->LINENotifyUserModel
            ->where('targetType', 'GROUP')
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
        $this->LINENotifyUserModel
            ->where('access_token', $token)
            ->update([
                'targetType' => $result['targetType'],
                'target' => $result['target'],
                'status' => $result['status']
            ]);
    }

    public function updateUserStatus($token, $status)
    {
        $this->LINENotifyUserModel
            ->where('access_token', $token)
            ->update([
                'status' => $status
            ]);
    }
}