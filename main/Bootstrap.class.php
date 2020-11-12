<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/Bootstrap.class.php
 * ファイル名：Bootstrap.class.php（設定に関するプログラム）
 */

namespace main;

date_default_timezone_set('Asia/Tokyo');

require_once dirname(__FILE__) . './../vendor/autoload.php';

class Bootstrap
{
    const DB_HOST = 'localhost';

    const DB_NAME = 'muscle_db';

    const DB_USER = 'muscle_user';

    const DB_PASS = 'muscle_pass';

    const DB_TYPE = 'mysql';

    const APP_DIR = '/Applications/XAMPP/xamppfiles/htdocs/muscle/';

    const TEMPLATE_DIR = self::APP_DIR . 'templates/muscle/';

    const CACHE_DIR = false;

    const APP_URL = 'http://localhost/muscle/';

    const ENTRY_URL = self::APP_URL . 'muscle/';

    public static function loadClass($class)
    {
        $path = str_replace('\\', '/', self::APP_DIR . $class . '.class.php');
        require_once $path;
    }
}

// これを実行しないとオートローダーとして動かない
spl_autoload_register([
    'main\Bootstrap',
    'loadClass'
]);
