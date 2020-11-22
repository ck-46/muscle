<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/index.php
 * ファイル名：index.php（トップページを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/index.php
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

$topThreeArr = $itm->getTopThree();

$context = [];

$context['topThreeArr'] = $topThreeArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('index.html.twig');
$template->display($context);