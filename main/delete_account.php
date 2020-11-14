<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/delete_account.php
 * ファイル名：delete_account.php（退会するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/delete_account.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\master\initMaster;
use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Account;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$acnt = new Account($db);
$ses = new Session($db);
$user_name = (isset($_SESSION['name']) === true) ? $_SESSION['name'] : '';

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// 初期データを設定
$dataArr = [
    'del_ctg_id' => '',
    'del_text' => ''
];

// エラーメッセージの定義、初期化
$errArr = [];
foreach ($dataArr as $key => $value) {
    $errArr[$key] = '';
}

$delArr = initMaster::getDeleteCategory();

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['delArr'] = $delArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('delete_account.html.twig');
$template->display($context);