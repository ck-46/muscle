<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/index.php
 * ファイル名：index.php（トップページをを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/index.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// セッションにセッションIDを設定する
$ses->checkSession();
$customer_no = $_SESSION['customer_no'];

// var_dump($_SESSION['log']);
// exit;

$context = [];
$context['customer_no'] = $customer_no;
$template = $twig->loadTemplate('index.html.twig');
$template->display($context);