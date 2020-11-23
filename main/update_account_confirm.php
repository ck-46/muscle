<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/update_account_confirm.php
 * ファイル名：update_account_confirm.php（アカウント情報変更の確認をするプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/update_account_confirm.php
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
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// 編集画面から来た場合
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
        $updateDataArr = $_POST;

        unset($updateDataArr['confirm']);

        $errArr = $acnt->updateCheck($updateDataArr);
        $err_check = $acnt->getUpdateErrorFlg($errArr);
        // err_check = false →エラーあり
        // err_check = true →エラーなし
        // エラーがなければcreate_account_confirm.html.twig、エラーがあればcreate_account.html.twig
        $template = ($err_check === true) ? 'update_account_confirm.html.twig' : 'update_account.html.twig';

    break;

    case 'back' :
        $updateDataArr = $_POST;
        
        unset($updateDataArr['back']);

        // エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($updateDataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'update_account.html.twig';
    break;

    case 'complete':
        $updateDataArr = $_POST;

        unset($updateDataArr['complete']);
        
        if ($updateDataArr['password'] !== '') {
            $updateDataArr['password'] = password_hash($updateDataArr['password'], PASSWORD_DEFAULT);
        }

        $res = $acnt->updateUserData($updateDataArr, $user_id);

        if ($res === true) {
            // 登録成功時は完了ページへ
            header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=update_account');
            exit();
        } else {
            // 登録失敗時は登録画面に戻る
            $template = 'update_account.html.twig';

            foreach ($updateDataArr as $key => $value) {
                $errArr[$key] = '';
            }
        }
    break;
}

$context = [];

$context['updateDataArr'] = $updateDataArr;
$context['errArr'] = $errArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate($template);
$template->display($context);