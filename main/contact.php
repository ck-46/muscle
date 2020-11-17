<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/contact.php
 * ファイル名：contact.php（お問い合わせページを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/contact.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\master\initMaster;
use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// 初期データを設定
$dataArr = [
    'contact_ctg_id' => '',
    'content' => ''
];

// エラーメッセージの定義、初期化
$errArr = [];
foreach ($dataArr as $key => $value) {
    $errArr[$key] = '';
}

$contactArr = initMaster::getContactCategory();

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['contactArr'] = $contactArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('contact.html.twig');
$template->display($context);