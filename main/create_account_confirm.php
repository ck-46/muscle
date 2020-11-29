<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/create_account_confirm.php
 * ファイル名：create_account_confirm.php（入力情報を確認するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/create_account_confirm.php
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

// 登録画面から来た場合
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

        $errArr = $acnt->errorCheck($dataArr);
        $err_check = $acnt->getErrorFlg();
        // err_check = false →エラーあり
        // err_check = true →エラーなし
        // エラーがなければcreate_account_confirm.html.twig、エラーがあればcreate_account.html.twig
        $template = ($err_check === true) ? 'create_account_confirm.html.twig' : 'create_account.html.twig';

    break;

    case 'back' :
        $dataArr = $_POST;

        unset($dataArr['back']);

        // エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'create_account.html.twig';
    break;

    case 'complete':
        $dataArr = $_POST;

        $dataArr['password'] = password_hash($dataArr['password'], PASSWORD_DEFAULT);

        // ↓この情報はいらないので外しておく
        unset($dataArr['complete']);
        unset($dataArr['entry_url']);
        unset($dataArr['confirm_email']);
        unset($dataArr['confirm_password']);

        $res = $acnt->insUserData($dataArr);

        if ($res === true) {
            $_SESSION = $ses->getSession($dataArr);
            // 登録成功時は完成ページへ
            header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=create_account');
            exit();
        } else {
            // 登録失敗時は登録画面に戻る
            $template = 'create_account.html.twig';

            foreach ($dataArr as $key => $value) {
                $errArr[$key] = '';
            }
        }
    break;
}

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate($template);
$template->display($context);