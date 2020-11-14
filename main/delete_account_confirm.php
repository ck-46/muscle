<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/delete_account_confirm.php
 * ファイル名：delete_account_confirm.php（入力情報を確認するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/delete_account_confirm.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\master\initMaster;
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

// 入力画面から来た場合
if (isset($_POST['confirm']) === true) {
    $mode = 'confirm';
}
// 修正するから来た場合
if (isset($_POST['back']) === true) {
    $mode = 'back';
}
// 登録完了
if (isset($_POST['complete']) === true) {
    $mode = 'complete';
}

switch ($mode) {
    case 'confirm' :
        unset($_POST['confirm']);

        $dataArr = $_POST;

        if (isset($_POST['del_ctg_id']) === false) {
            $dataArr['del_ctg_id'] = '';
        }

        $errArr = $acnt->deleteCheck($dataArr);
        $err_check = $acnt->getErrorFlg();
        // err_check = false →エラーあり
        // err_check = true →エラーなし
        // エラーがなければdelete_account_confirm.html.twig、エラーがあればdelete_account.html.twig
        $template = ($err_check === true) ? 'delete_account_confirm.html.twig' : 'delete_account.html.twig';

        $delArr = initMaster::getDeleteCategory();

    break;

    case 'back' :
        $dataArr = $_POST;

        unset($dataArr['back']);

        // エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'delete_account.html.twig';

        $delArr = initMaster::getDeleteCategory();

    break;

    case 'complete':
        $mem_id = (isset($_SESSION['mem_id']) === true) ? $_SESSION['mem_id'] : '';

        $dataArr = $_POST;
        unset($dataArr['complete']);

        $res = $acnt->delMemberData($mem_id, $dataArr);
        if ($res === true) {
            $result = $acnt->recordDelReason($mem_id, $dataArr);
            if ($result === true) {
                header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=delete_account');
                exit();
            }
        } else {
            // 退会失敗時は入力画面に戻る
            $template = 'delete_account.html.twig';
            foreach ($dataArr as $key => $value) {
                $errArr[$key] = '';
            }
        }

    break;
}

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['delArr'] = $delArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate($template);
$template->display($context);