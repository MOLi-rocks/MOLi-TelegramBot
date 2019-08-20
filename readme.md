# MOLi-TelegramBot
![Laravel Version](https://img.shields.io/badge/Laravel-5.5-brightgreen.svg)
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.0-orange.svg)
[![CircleCI](https://circleci.com/gh/MOLi-rocks/MOLi-TelegramBot/tree/master.svg?style=svg)](https://circleci.com/gh/MOLi-rocks/MOLi-TelegramBot/tree/master)

## 開發環境說明

### 編輯器
可以使用 PhpStorm 當作 IDE，學生可以免費使用授權版，此 IDE 非常強大，但是也因為 Laravel magic function 太多導致無法精確的判斷，可以使用 [laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) 來幫助 IDE 識別。

### 開發環境（A、B 擇一使用）

#### A. Laradock（推薦）
如遇資料庫問題請將 MySQL 改成 5.7 版，別用 latest

> **Windows 使用者請注意**  
> Docker 在 pull 時必須使用 Windows 端的 git，不然會無法運行，且 Docker 請使用 Toolbox 版本。

#### B. 實體安裝

##### Windows
你可以透過 XAMPP 或是 AppServ 來建構 PHP/MySQL 環境，不建議用於正式環境

##### Linux
裝好 PHP 與 MySQL 環境即可。

##### Mac
自帶 PHP， 可以透過 brew 安裝 MariaDB。

## 開始部署

1. 將專案 pull 下來後，請 `cp .env-example .env` 並且設定好 `.env` 裡面的參數
2. 再使用 `composer install` 去下載必要套件，如機器上缺乏某些套件在此時也會跳出通知並暫停
3. `php artisan key:generate` 來產生 key，用於保護 session
4. `php artisan migrate` 以進行資料庫遷移
5. `php artisan serve` 即可運行，或是將 Web Server root 指向 `{your-project}/public/` 目錄

## 教學文件

1. [5.5 版官網英文文件](<https://laravel.com/docs/5.5>)
2. [英文影片教學](<https://laracasts.com/>)
3. [中文文件](<https://laravel.tw/>)
