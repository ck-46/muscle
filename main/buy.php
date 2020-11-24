<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/buy.php
 * ファイル名：buy.php（購入ページを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/buy.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Cart;
use main\lib\Account;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';
$cart = new Cart($db);
$acnt = new Account($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$userData = $acnt->getUserData($user_id);

list($sumNum, $sumPrice) = $cart->getItemAndSumPrice($user_id);
$dataArr = $cart->getCartData($user_id);

$context = [];

$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['dataArr'] = $dataArr;
$context['userData'] = $userData;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('buy.html.twig');
$template->display($context);