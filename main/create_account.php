<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/create_account.php
 * ファイル名：create_account.php（新規登録をするプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/create_account.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// 登録済みのユーザーが来た時にログインページに遷移させる
// SESSIONを使えばいけそう

// 初期データを設定
$dataArr = [
    'family_name' => '',
    'first_name' => '',
    'email' => '',
    'password' => ''
];
$confirmDataArr = [
    'confirm_email' => '',
    'confirm_password' => ''
];

// エラーメッセージの定義、初期化
$errArr = [];
foreach ($dataArr as $key => $value) {
    $errArr[$key] = '';
}
foreach ($confirmDataArr as $key => $value) {
    $errArr[$key] = '';
}

$context = [];

$context['dataArr'] = $dataArr;
$context['confirmDataArr'] = $confirmDataArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate('create_account.html.twig');
$template->display($context);

