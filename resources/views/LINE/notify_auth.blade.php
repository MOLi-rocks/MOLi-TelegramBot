<!DOCTYPE html>
<html>
<head>
    <title>暨大最新公告 LINE Notify</title>
    <link rel='icon' href='https://moli.rocks/favicon.ico' />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
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
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            margin-bottom: 150px; /* Margin bottom by footer height */
        }

        .line-logo {
            color: #4CAF50;/* LINE Green */
        }

        .center-text {
            text-align: center;
            vertical-align: center;
        }

        .padding-top {
            padding-top: 1em;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 150px; /* Set the fixed height of the footer here */
            background-color: #f5f5f5;
        }

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
    <div class="container">
        <div class="row padding-top">
            <div class="col center-text">
                <i class="fab fa-line fa-10x line-logo"></i>
            </div>
        </div>
        @if (isset($success) and $success == true)
            <div class="row padding-top">
                <div class="col center-text">
                    <h1>恭喜完成連動！</h1>
                </div>
            </div>
            <div class="row padding-top">
                <div class="col center-text">
                    <h1>請查看 LINE 通知，並確認 LINE Notify 並沒有被封鎖！</h1>
                </div>
            </div>
        @elseif (isset($error))
            <div class="row padding-top">
                <div class="col center-text">
                    <h1>發生錯誤！</h1>
                </div>
            </div>
            <div class="row padding-top">
                <div class="col center-text">
                    <h2>錯誤代碼：{{ $error }}</h2>
                </div>
            </div>
            <div class="row padding-top">
                <div class="col center-text">
                    <p>如問題持續發生，請聯絡 MOLi 實驗室，並且告知錯誤代碼</p>
                    <p>If the problem continues, please contact the MOLi Lab and tell us Error Code</p>
                </div>
            </div>
            <div class="row padding-top">
                <div class="col center-text">
                    <a href="{{ Route('line_notify_auth') }}"><button class="button button1">重新嘗試連動</button></a>
                </div>
            </div>
        @else
            <div class="row padding-top">
                <div class="col center-text">
                    <h2>點擊按鈕後，選取「透過1對1聊天接收LINE Notify的通知」，並且同意連動</h2>
                </div>
            </div>
            <div class="row padding-top">
                <div class="col center-text">
                    <div class="alert alert-danger" role="alert">
                        切勿重複申請，會造成同樣訊息多次收到。
                    </div>
                </div>
            </div>
            <div class="row padding-top">
                <div class="col center-text">
                    <button class="button button1" onclick="oAuth2();">點此申請暨大最新公告 LINE Notify</button>
                </div>
            </div>
        @endif
    </div>
    <footer class="footer">
        <div class="container padding-top">
            <div class="row">
                <div class="col center-text">
                    聯絡我們 Contact Us
                </div>
            </div>
            <div class="row">
                <div class="col-7 padding-top">
                    <P>服務供應商：MOLi 實驗室</P>
                    <P>地址：南投縣埔里鎮大學路 470 號管理學院 237 室</P>
                </div>
                <div class="col-2 center-text">
                    <a href="https://telegram.me/MOLi_rocks" target="_blank"><i class="fab fa-telegram fa-4x"></i></a><br />Telegram
                </div>
                <div class="col center-text">
                    <a href="https://www.facebook.com/MOLi.rocks" target="_blank"><i class="fab fa-facebook-square fa-4x"></i></a><br />Facebook
                </div>
            </div>
        </div>
    </footer>
</body>
</html>