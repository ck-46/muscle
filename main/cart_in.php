<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/cart_in.php
 * ファイル名：cart_in.php（商品をカートに追加するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/cart-in.php
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

// ログインしていない場合はログインページへ遷移
// ログインできたら商品がカートに追加された状態のカートに遷移
if (isset($_SESSION['user_id']) === true) {
    $user_id = $_SESSION['user_id'];
} else {

    $_SESSION['post'] = $_POST;
    $_SESSION['route'] = 'cart_in';
    $_SESSION['url'] = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    header('Location: ' . Bootstrap::ENTRY_URL . 'login.php');
    exit();
}

if (isset($_POST['cart_in']) === true) {
    $dataArr = $_POST;
} elseif (isset($_SESSION['post']) === true) {
    $dataArr = $_SESSION['post'];
    $_SESSION['post'] = '';
}

$item_id = (preg_match('/^\d+$/', $dataArr['item_id']) === 1) ? $dataArr['item_id'] : '';

if ($dataArr['amount'] === '0' || $dataArr['amount'] === '') {
    header('Location:' . Bootstrap::ENTRY_URL. 'detail.php?item_id=' . $item_id . '&amount=0');
} elseif (preg_match('/^[0-9]+$/', $dataArr['amount']) === 1) {
    $amount = $dataArr['amount'];
}

// カートの情報を取得し、同じ商品があればupdate、なければinsert
$cartArr = $cart->getCartData($user_id);

$res = '';

if (count($cartArr) !== 0) {
    foreach ($cartArr as $key => $value) {
        if ($value['item_id'] === $item_id) {
            $insAmount = $value['num'] + $amount;
            $res = $cart->updateCartData($item_id, $user_id, $insAmount);
        }
    }
    if ($res === '') {
        $res = $cart->insCartData($user_id, $item_id, $amount);
    }
} else {
    $res = $cart->insCartData($user_id, $item_id, $amount);
}

if ($res === true) {
    header('Location: ' . Bootstrap::ENTRY_URL . 'cart.php');
    exit();
}