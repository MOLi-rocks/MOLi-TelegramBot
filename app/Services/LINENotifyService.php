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
            $json = json_decode($response, true);

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

    public function getAllToken()
    {
        return $this->lineNotifyUserRepository->getAllToken();
    }

    public function sendMsg($access_token, $msg)
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('POST', 'https://notify-api.line.me/api/notify', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'form_params' => [
                    'message' => $msg,
                ],
                'timeout' => 10
            ]);

            return $response;
        } catch (GuzzleHttpTransferException $e) {
            $status = $e->getCode();
            if ($status == 400) {
                throw new \Exception('400 - Unauthorized request');
            } elseif ($status == 401) {
                throw new \Exception('401 -  Invalid access token');
            } elseif ($status == 500) {
                throw new \Exception('500 - Failure due to server error');
            } else {
                throw new \Exception('Processed over time or stopped');
            }
        }
    }
}