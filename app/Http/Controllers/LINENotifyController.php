<?php

namespace MOLiBot\Http\Controllers;

use Illuminate\Http\Request;
use MOLiBot\LINE_Notify_User;
use SoapBox\Formatter\Formatter;
use \GuzzleHttp\Client as GuzzleHttpClient;
use \GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;


class LINENotifyController extends Controller
{

    private $redirect_uri;
    private $client_id;
    private $client_secret;

    public function __construct()
    {
        $this->redirect_uri = \Config::get('line.line_notify_redirect_uri');
        $this->client_id = \Config::get('line.line_notify_client_id');
        $this->client_secret = \Config::get('line.line_notify_client_secret');
    }

    public static function sendMsg($access_token, $msg)
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
        return $response;
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
            $formatter = Formatter::make($response, Formatter::JSON);
            $json = $formatter->toArray();

            return $json;
        } catch (GuzzleHttpTransferException $e) {
            return $e;
        }

    }

    public function auth(Request $request)
    {
        $code = $request->query('code', false);
        $state = $request->query('state', false);
        $stats = $request->exists('stats');
        if ($code) {
            $client = new GuzzleHttpClient();
            // get access_token
            try {
                $response = $client->request('POST', 'https://notify-bot.line.me/oauth/token', [
                    'headers' => [
                        'User-Agent' => 'MOLi Bot',
                        'cache-control' => 'no-cache'
                    ],
                    'form_params' => [
                        'grant_type' => 'authorization_code',
                        'code' => $code,
                        'redirect_uri' => $this->redirect_uri,
                        'client_id' => $this->client_id,
                        'client_secret' => $this->client_secret
                    ],
                    'timeout' => 10
                ]);

                $response = $response->getBody()->getContents();
                $formatter = Formatter::make($response, Formatter::JSON);
                $json = $formatter->toArray();
                $access_token = $json['access_token'];
                $success = true;
                LINE_Notify_User::create([
                    'access_token' => $access_token
                    ]);
            } catch (GuzzleHttpTransferException $e) {
                $status = $e->getCode();
                if ($status == 400) {
                    $error = '400 - Unauthorized request';
                    return view('LINE/notify_auth', compact('error'));
                } else {
                    $error = 'Other - Processed over time or stopped';
                    return view('LINE/notify_auth', compact('error'));
                }
            }

            // send a welcome message
            try {
                $msg = "\n歡迎使用暨大通知，此服務由 MOLi 實驗室維護\n如有疑問可至粉專或群組詢問\nhttps://moli.rocks";
                $this->sendMsg($access_token, $msg);
            } catch (\Exception $e) {
                return $e->getCode();
            }

            // get status
            try {
                $json = $this->getStatus($access_token);
                LINE_Notify_User::where('access_token', $access_token)
                    ->update([
                        'targetType' => $json['targetType'],
                        'target' => $json['target']
                    ]);
            } catch (\Exception $e) {
                return $e->getCode();
            }

            return view('LINE/notify_auth', compact('success'));

        } elseif ($stats) {
            // 回報 JSON 數據
            return response()->json(LINE_Notify_User::getStats());
        } else {
            // 歡迎畫面
            $client_id = $this->client_id;
            $redirect_uri = $this->redirect_uri;
            return view('LINE/notify_auth', compact('client_id', 'redirect_uri'));
        }
    }
}
