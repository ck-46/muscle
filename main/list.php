<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/list.php
 * ファイル名：list.php（商品一覧の表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/list.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['name']) === true) ? $_SESSION['name'] : '';
$itm = new Item($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$ctg_id = '';

// フレーバー別
if (isset($_GET['flavor']) === true && preg_match('/^[0-9]+$/', $_GET['flavor']) === 1) {
    $ctg_id = 'flavor';
}
// 目的別
if (isset($_POST['purpose']) === true && preg_match('/^[0-9]+$/', $_GET['purpose']) === 1) {
    $ctg_id = 'purpose';
}
// ブランド別
if (isset($_POST['brand']) === true && preg_match('/^[0-9]+$/', $_GET['purpose']) === 1) {
    $ctg_id = 'brand';
}

// 商品リストを取得する
$dataArr = $itm->getItemList($ctg_id);

$context = [];

$context['dataArr'] = $dataArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('list.html.twig');
$template->display($context);