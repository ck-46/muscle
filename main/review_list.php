<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/review_list.php
 * ファイル名：review_list.php（レビュー一覧を表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/review_list.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Account;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';
$acnt = new Account($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// レビューを取得する
$reviewList = $acnt->getReviewList($user_id);
// 降順で表示
arsort($reviewList);

$context = [];

$context['reviewList'] = $reviewList;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('review_list.html.twig');
$template->display($context);