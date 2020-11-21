<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/review_confirm.php
 * ファイル名：review_confirm.php（入力情報を確認するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/review_confirm.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Account;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$acnt = new Account($db);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';

// 確認するから来た場合
if (isset($_POST['confirm']) === true) {
    $mode = 'confirm';
}
// 修正するから来た場合
if (isset($_POST['fix']) === true) {
    $mode = 'fix';
}
// 修正するから来た場合
if (isset($_POST['back']) === true) {
    $mode = 'back';
}
// 投稿するから来た場合
if (isset($_POST['complete']) === true) {
    $mode = 'complete';
}

switch ($mode) {
    case 'confirm' :
        unset($_POST['confirm']);

        $dataArr = $_POST;

        // var_dump($dataArr);
        // exit;

        $errArr = $acnt->reviewCheck($dataArr);
        $err_check = $acnt->getErrorFlg();
        // err_check = false →エラーあり
        // err_check = true →エラーなし
        // エラーがなければcreate_account_confirm.html.twig、エラーがあればcreate_account.html.twig
        $template = ($err_check === true) ? 'review_confirm.html.twig' : 'review.html.twig';
    break;

    case 'fix' :
        unset($_POST['fix']);

        $dataArr = $_POST;

        // var_dump($dataArr);
        // exit;

        // エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'review.html.twig';
    break;

    case 'back' :
        header('Location: ' . Bootstrap::ENTRY_URL . 'buy_history.php');
        exit();
    break;

    case 'complete':
        unset($_POST['complete']);
        unset($_POST['item_name']);
        unset($_POST['detail']);
        unset($_POST['price']);
        unset($_POST['image']);

        $dataArr = $_POST;
        // var_dump($dataArr);
        // exit;

        $res = $acnt->insReviewData($dataArr, $user_id);

        if ($res === true) {
            // 登録成功時は完了ページへ
            header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=review');
            exit();
        }
    break;
}

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate($template);
$template->display($context);