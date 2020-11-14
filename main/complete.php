<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/complete.php
 * ファイル名：complete.php（完了ページを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/complete.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['name']) === true) ? $_SESSION['name'] : '';

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$content = [];

if (isset($_GET['key']) === true) {
    // 新規登録から来た場合
    if ($_GET['key'] === 'create_account') {
        $content['type'] = '新規登録';
        $content['page'] = 'ログインページへ';
    }
    // ログインから来た場合
    if ($_GET['key'] === 'login') {
        $content['type'] = 'ログイン';
        $content['name'] = $_SESSION['name'];
    }
    // ログアウトから来た場合
    if ($_GET['key'] === 'logout') {
        $content['type'] = 'ログアウト';
        $content['page'] = 'ログインページへ';
    }
    // 退会から来た場合
    if ($_GET['key'] === 'delete_account') {
        $content['type'] = '退会手続き';
    }
}

$context = [];

$context['content'] = $content;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('complete.html.twig');
$template->display($context);