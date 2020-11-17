<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/update_account.php
 * ファイル名：update_account.php（アカウント情報の変更ページを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/update_account.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Account;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$acnt = new Account($db);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// 現在のデータを取得
$dataArr = $acnt->getUserData($user_id);
// var_dump($dataArr);
// exit;

$context = [];

$context['dataArr'] = $dataArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('update_account.html.twig');
$template->display($context);