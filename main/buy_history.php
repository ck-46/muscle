<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/buy_history.php
 * ファイル名：buy_history.php（購入履歴ページを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/buy_history.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Cart;
use main\lib\Account;
use main\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';
$cart = new Cart($db);
$acnt = new Account($db);
$itm = new Item($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$buy_history = $acnt->getBuyHistory($user_id);
$item_data = $itm->getAllList();

$context = [];

$context['buy_history'] = $buy_history;
$context['item_data'] = $item_data;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('buy_history.html.twig');
$template->display($context);