<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/review_list_update.php
 * ファイル名：review_list_update.php（レビュー編集ページを表示、レビューの削除をするプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/review_list_update.php
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

$review_id = (isset($_GET['review_id']) === true) ? $_GET['review_id'] : '';

if (isset($_GET['mode']) === true) {
    if ($_GET['mode'] === 'update') $mode = $_GET['mode'];
    if ($_GET['mode'] === 'delete') $mode = $_GET['mode'];
}

// 確認するから来た場合
if (isset($_POST['confirm']) === true) {
    $mode = 'confirm';
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
    case 'update' :

        $res = $acnt->getReviewd($review_id);

        $dataArr = $res[0];

        // var_dump($dataArr);
        // exit;

        $template = 'review_update.html.twig';
    break;

    case 'delete' :

        $res = $acnt->delReview($review_id);

        if ($res === true) {
            // 完了ページへ
            header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=review_delete');
            exit();
        }
    break;

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
        $template = ($err_check === true) ? 'review_update_confirm.html.twig' : 'review_update.html.twig';
    break;

    case 'fix' :
        unset($_POST['fix']);

        $dataArr = $_POST;
        // エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'review_update.html.twig';
    break;

    case 'complete' :
        $review_id = $_POST['review_id'];
        $content= $_POST['content'];

        $res = $acnt->updateReview($review_id, $content);

        if ($res === true) {
            // 登録成功時は完了ページへ
            header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=review_update');
            exit();
        }
}

$context = [];

$context['dataArr'] = $dataArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate($template);
$template->display($context);