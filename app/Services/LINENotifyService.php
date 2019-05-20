<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\LINENotifyUserRepository;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

class LINENotifyService
{
    private $lineNotifyUserRepository;

    public function __construct(LINENotifyUserRepository $LINENotifyUserRepository)
    {
        $this->lineNotifyUserRepository = $LINENotifyUserRepository;
    }

    public function getStatus($access_token)
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://notify-api.line.me/api/status', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'timeout' => 10
            ]);

            $response = $response->getBody()->getContents();
            $json = json_decode($response)->toArray();

            return $json;
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }
    }

    public function updateStatus($token)
    {
        $result = $this->getStatus($token);

        if (is_array($result)) {
            $this->lineNotifyUserRepository->updateUserData($token, $result);
        } else {
            $this->lineNotifyUserRepository->updateUserStatus($token, $result);
        }
    }

    public function getAllStats()
    {
        $total = $this->lineNotifyUserRepository->getTotalStats();

        $user = $this->lineNotifyUserRepository->getUserStats();

        $group = $this->lineNotifyUserRepository->getGroupStats();

        return [
            'Total' => $total,
            'USER' => $user,
            'GROUP' => $group
        ];
    }

    public function createToken($token)
    {
        $this->lineNotifyUserRepository->createToken($token);
    }
}