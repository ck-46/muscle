<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/login.php
 * ファイル名：login.php（ログインフォームを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/login.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['name']) === true) ? $_SESSION['name'] : '';

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// 初期データを設定
$dataArr = [
    'email' => '',
    'password' => ''
];

// エラーメッセージの定義、初期化
$errArr = [];
foreach ($dataArr as $key => $value) {
    $errArr[$key] = '';
}

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('login.html.twig');
$template->display($context);
