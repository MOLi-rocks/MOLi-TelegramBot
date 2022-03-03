# MOLi-TelegramBot
![Laravel Version](https://img.shields.io/badge/Laravel-7.x-brightgreen.svg)
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.2.5-orange.svg)
[![CircleCI](https://circleci.com/gh/MOLi-rocks/MOLi-TelegramBot/tree/master.svg?style=svg)](https://circleci.com/gh/MOLi-rocks/MOLi-TelegramBot/tree/master)
[![State-of-the-art Shitcode](https://img.shields.io/static/v1?label=State-of-the-art&message=Shitcode&color=7B5804)](https://github.com/trekhleb/state-of-the-art-shitcode)

## 開發環境說明

### PHP 版本
雖然依賴只須 >= 7.2.5，但是因為 PHP 支援版本的原因，所以開發是使用 7.4

### 編輯器
可以使用 PhpStorm 當作 IDE，學生可以免費使用授權版，此 IDE 非常強大，但是也因為 Laravel magic function 太多導致無法精確的判斷，可以使用 [laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) 來幫助 IDE 識別。

### 開發環境（A、B 擇一使用）

#### A. Laradock（推薦）
如遇資料庫問題請將 MySQL 改成 5.7 版，別用 latest

#### B. 實體安裝

##### Windows
你可以透過 XAMPP 或是 AppServ 來建構 PHP/MySQL 環境，不建議用於正式環境

##### Linux
裝好 PHP 與 MySQL 環境即可。

##### Mac
自帶 PHP， 可以透過 brew 安裝 MariaDB。

## Laravel 設定

1. clone 本專案
2. 使用 `composer install` 安裝依賴套件，有任何問題在此時會跳出通知並暫停（例如：PHP 版本不符）
   > 如在正式環境可加上 `--no-dev` 參數，將只安裝必要套件
3. 將專案內的 `.env.example` 複製為 `.env`
4. `php artisan key:generate` 來產生 key，用於保護 session
5. 設定資料庫連線（.env 檔）
    1. `DB_HOST` 請設定為自己的 DB 主機 IP 或 Domain
    2. `DB_DATABASE` 請設定為要給 Laravel 使用的資料庫名稱
    3. `DB_USERNAME` 請設定資料庫連線帳號
    4. `DB_PASSWORD` 請設定資料庫連線密碼
    5. 以上設定完成後可使用 `php artisan migrate` 產生資料庫結構
6. `php artisan serve` 即可運行，或是將 Web Server root 指向 `{your-project}/public/` 目錄

## 資料來源
- [MOLi Blog](https://blog.moli.rocks/)
- [MOLi@KKTIX](https://moli.kktix.cc/)
- [NCNU OpenData API](https://api.ncnu.edu.tw/)
- [民生示警公開資料平台](https://alerts.ncdr.nat.gov.tw/)
- [台灣中油牌價 DataSet](https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx)

## 教學文件

1. [Laravel 7.x 官方文件](https://laravel.com/docs/7.x)
2. [英文影片教學](https://laracasts.com/)
3. [Laradock](https://laradock.io/)
