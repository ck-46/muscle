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
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';
$cart = new Cart($db);

// テンプレート指定
// $loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
// $twig = new \Twig_Environment($loader, [
//     'cache' => Bootstrap::CACHE_DIR
// ]);

// var_dump($_POST['amount']);
// exit;

if (isset($_POST['cart_in']) === true) {
    $item_id = (preg_match('/^\d+$/', $_POST['item_id']) === 1) ? $_POST['item_id'] : '';
    // var_dump($item_id);
    // exit;
    // $amount = (preg_match('/^\d+$/', $_POST['amount']) === 1 ) ? $_POST['amount'] : '';
    // if (preg_match('/^[0-9]+$/', $_POST['amount']) === 1) {
    //     $amount = $_POST['amount'];
    // } elseif ($_POST['amount'] === '0' || $_POST['amount'] === '') {
    //     header('Location:' . Bootstrap::ENTRY_URL. 'detail.php?item_id=' . $item_id . '&amount=0');
    // }

    if ($_POST['amount'] === '0' || $_POST['amount'] === '') {
        header('Location:' . Bootstrap::ENTRY_URL. 'detail.php?item_id=' . $item_id . '&amount=0');
    } elseif (preg_match('/^[0-9]+$/', $_POST['amount']) === 1) {
        $amount = $_POST['amount'];
    }

    // var_dump($amount);
    // exit;

    $res = $cart->insCartData($user_id, $item_id, $amount);

    if ($res === true) {
        header('Location: ' . Bootstrap::ENTRY_URL . 'cart.php');
        exit();
    }
}