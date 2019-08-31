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

    /**
     * @param $access_token string
     * @param $msg string
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMsg($access_token, $msg)
    {
        return $this->lineNotifyService->sendMsg($access_token, $msg);
    }

    /**
     * @param $access_token string
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStatus($access_token)
    {
        return $this->lineNotifyService->getStatus($access_token);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function code(Request $request)
    {
        $state = $request->input('state', 'NO_STATE');

        $lineCodeUrl = 'https://notify-bot.line.me/oauth/authorize?' .
            'response_type=code' .
            '&client_id=' . $this->client_id .
            '&redirect_uri=' . $this->redirect_uri .
            '&scope=notify' .
            '&state=' . $state;

        return redirect($lineCodeUrl);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function auth(Request $request)
    {
        $code = $request->query('code', false);

        if ($code) {
            $resToken = $this->lineNotifyService->getToken(
                $code,
                $this->redirect_uri,
                $this->client_id,
                $this->client_secret
            );

            if ($resToken['success']) {
                // send a welcome message
                $msg = PHP_EOL . '歡迎使用暨大通知，此服務由 MOLi 實驗室維護' . PHP_EOL .
                    '如有疑問可至粉專或群組詢問' . PHP_EOL .
                    'https://moli.rocks';

                $resToken = $resToken['token'];

                $this->lineNotifyService->updateUser($resToken);

                $this->sendMsg($resToken, $msg);
            }

            return view('LINE.notify_auth', compact('resToken'));
        } else {
            // 歡迎畫面
            return view('LINE.notify_auth');
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function stats()
    {
        $stats = $this->lineNotifyService->getAllStats();
        return view('LINE.stats', compact('stats'));
    }
}
