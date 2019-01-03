<!DOCTYPE html>
<html lang="tw">
<head>
    <title>暨大通知</title>
    <link rel='icon' href='https://moli.rocks/favicon.ico' />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (isset($client_id) and isset($redirect_uri))
    <script>
        function oAuth2() {
            var URL = 'https://notify-bot.line.me/oauth/authorize?';
            URL += 'response_type=code';
            URL += '&client_id={{ $client_id }}';
            URL += '&redirect_uri={{ $redirect_uri }}';
            URL += '&scope=notify';
            URL += '&state=NO_STATE';
            window.location.href = URL;
        }
    </script>
    @endif
    <style>
        .button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 16px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            cursor: pointer;
        }

        .button1 {
            background-color: white;
            color: black;
            border: 2px solid #4CAF50;
        }

        .button1:hover {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
@if (isset($success) and $success == true)
    <h1>恭喜完成連動！ </h1>
    <h1>請查看 LINE 通知，並確認 LINE Notify 並沒有被封鎖！</h1>
@elseif (isset($error))
    <h1>發生錯誤！</h1>
    <h2>錯誤代碼: {{ $error }}</h2>
    <h2>請重新連動！</h2>
    <pre>
        如問題持續發生，請聯絡 MOLi 實驗室，並且告知錯誤代碼
        地址
        南投縣埔里鎮大學路 470 號管理學院 237 室

        If the problem continues, please contact the MOLi Lab and tell us Error Code
        Address
        Room 237 in Management Building, in National Chi-Nan University.
    </pre>

    <a href="{{ Route('line_notify_auth') }}"><button class="button button1">重新嘗試連動</button></a>
@else
    <h2>點擊按鈕後，選取「透過1對1聊天接收LINE Notify的通知」，並且同意連動</h2>
    <p>切勿重複申請，會造成同樣訊息多次收到。</p>
    <button class="button button1" onclick="oAuth2();"> 點此申請暨大通知 LINE Notify</button>
    <br><br><br>
    <pre>
        服務供應商：
            MOLi 實驗室
        地址：
            南投縣埔里鎮大學路 470 號管理學院 237 室
    </pre>

    <a href="https://telegram.me/MOLi_rocks" target="_blank">Telegram 發問群組</a>
    <br>
    <a href="https://www.facebook.com/MOLi.rocks" target="_blank">FB 粉專</a>

@endif
</body>
</html>