<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/login_confirm.php
 * ファイル名：login_confirm.php（入力情報を確認するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/login_confirm.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Account;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$acnt = new Account($db);
$ses = new Session($db);
$user_name = (isset($_SESSION['name']) === true) ? $_SESSION['name'] : '';

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);


if (isset($_POST['confirm']) === true) {

    unset($_POST['confirm']);

    $dataArr = $_POST;

    $errArr = $acnt->loginCheck($dataArr);
    $err_check = $acnt->getErrorFlg();
    // err_check = false →エラーあり
    // err_check = true →エラーなし
    // エラーがなければcomplete.php、エラーがあればlogin.html.twig
    if ($err_check === true) {
        if ($_SESSION['route'] === 'cart') {
            $url = $_SESSION['url'];
            $_SESSION = array();
            $_SESSION = $ses->getSession($dataArr);
            header('Location: ' . $url);
            exit();

        }
        // ユーザー情報をセッションに保存
        $_SESSION = $ses->getSession($dataArr);

        header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=login');
        exit();
    } else {
        $template = 'login.html.twig';
    }
}

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate($template);
$template->display($context);