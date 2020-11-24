<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/cart.php
 * ファイル名：cart.php（カートを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/cart.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Cart;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$cart = new Cart($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// ログインしていない場合はログインページへ遷移
// ログインできたら商品がカートに追加された状態のカートに遷移
if (isset($_SESSION['user_id']) === true) {
    $user_id = $_SESSION['user_id'];
} else {
    $_SESSION['route'] = 'cart';
    $_SESSION['url'] = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header('Location: ' . Bootstrap::ENTRY_URL . 'login.php');
    exit();
}

// item_idを取得する
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/', $_GET['item_id']) === 1) ? $_GET['item_id'] : '';
$crt_id = (isset($_GET['crt_id']) === true && preg_match('/^\d+$/', $_GET['crt_id']) === 1) ? $_GET['crt_id'] : '';

// del_item_idが設定されていれば、削除する
if (isset($_GET['del_item_id']) === true) {
    $del_item_id = $_GET['del_item_id'];
    $cart->delCartData($del_item_id, $user_id);
}

// アイテム数と合計金額を取得する
// listは配列をそれぞれの変数に分ける
list($sumNum, $sumPrice) = $cart->getItemAndSumPrice($user_id);

// カートの情報を取得する
$dataArr = $cart->getCartData($user_id);

$context = [];

$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['dataArr'] = $dataArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('cart.html.twig');
$template->display($context);