<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;
use MOLiBot\Services\LINENotifyService;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;

class LINENotifyController extends Controller
{
    private $redirect_uri;
    private $client_id;
    private $client_secret;
    private $lineNotifyService;

    public function __construct(LINENotifyService $LINENotifyService)
    {
        $this->redirect_uri = config('line.line_notify_redirect_uri');
        $this->client_id = config('line.line_notify_client_id');
        $this->client_secret = config('line.line_notify_client_secret');

        $this->lineNotifyService = $LINENotifyService;
    }

    public function sendMsg($access_token, $msg)
    {
        try {
            return $this->lineNotifyService->sendMsg($access_token, $msg);
        } catch (\Exception $e) {
            return $e->getCode();
        }
    }

    public function getStatus($access_token)
    {
        return $this->lineNotifyService->getStatus($access_token);
    }

    public function auth(Request $request)
    {
        $code = $request->query('code', false);

        if ($code) {
            $client = new GuzzleHttpClient();
            // get access_token
            try {
                $response = $client->request('POST', 'https://notify-bot.line.me/oauth/token', [
                    'headers'     => [
                        'User-Agent'    => 'MOLi Bot',
                        'cache-control' => 'no-cache'
                    ],
                    'form_params' => [
                        'grant_type'    => 'authorization_code',
                        'code'          => $code,
                        'redirect_uri'  => $this->redirect_uri,
                        'client_id'     => $this->client_id,
                        'client_secret' => $this->client_secret
                    ],
                    'timeout'     => 10
                ]);

                $response = $response->getBody()->getContents();
                $json = json_decode($response)->toArray();
                $access_token = $json['access_token'];
                $success = true;
                $this->lineNotifyService->createToken($access_token);
            } catch (GuzzleHttpTransferException $e) {
                $status = $e->getCode();
                if ($status == 400) {
                    $error = '400 - Unauthorized request';
                    return view('LINE.notify_auth', compact('error'));
                } else {
                    $error = 'Other - Processed over time or stopped';
                    return view('LINE.notify_auth', compact('error'));
                }
            }

            // send a welcome message
            try {
                $msg = PHP_EOL .'歡迎使用暨大通知，此服務由 MOLi 實驗室維護' . PHP_EOL .
                    '如有疑問可至粉專或群組詢問' . PHP_EOL .
                    'https://moli.rocks';

                $this->sendMsg($access_token, $msg);
            } catch (\Exception $e) {
                return $e->getCode();
            }

            // update status
            $this->lineNotifyService->updateStatus($access_token);

            return view('LINE.notify_auth', compact('success'));
        } else {
            // 歡迎畫面
            $client_id = $this->client_id;
            $redirect_uri = $this->redirect_uri;
            return view('LINE.notify_auth', compact('client_id', 'redirect_uri'));
        }
    }

    public function stats()
    {
        $stats = $this->lineNotifyService->getAllStats();
        return view('LINE.stats', compact('stats'));
    }
}
