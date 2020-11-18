<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/detail.php
 * ファイル名：detail.php（商品詳細を表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/detail.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$itm = new Item($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// item_idを取得する
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/', $_GET['item_id']) === 1) ? $_GET['item_id'] : '';

// item_idが取得できていない場合、商品一覧へ遷移させる
if ($item_id === '') {
    header('Location:' . Bootstrap::ENTRY_URL. 'list.php');
}

$errMsg = (isset($_GET['amount']) === true) ? '数量を入力してください' : '';

// 商品情報を取得する
$itemData = $itm->getItemDetailData($item_id);

$itemData['amount'] = '';

// 数量
$amountArr = $itm->getAmount();

$context = [];

$context['itemData'] = $itemData[0];
$context['amountArr'] = $amountArr;
$context['errMsg'] = $errMsg;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('detail.html.twig');
$template->display($context);