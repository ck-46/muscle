<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/contact_confirm.php
 * ファイル名：contact_confirm.php（入力情報を確認するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/contact_confirm.php
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
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';

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
// 完了
if (isset($_POST['complete']) === true) {
    $mode = 'complete';
}

switch ($mode) {
    case 'confirm' :
        unset($_POST['confirm']);

        $dataArr = $_POST;

        // var_dump($dataArr);
        // exit;

        if (isset($_POST['contact_ctg_id']) === false) {
            $dataArr['contact_ctg_id'] = '';
        }

        $errArr = $acnt->contactCheck($dataArr);
        $err_check = $acnt->getErrorFlg();
        // err_check = false →エラーあり
        // err_check = true →エラーなし
        // エラーがなければdelete_account_confirm.html.twig、エラーがあればdelete_account.html.twig
        $template = ($err_check === true) ? 'contact_confirm.html.twig' : 'contact.html.twig';

        $contactArr = initMaster::getContactCategory();

    break;

    case 'back' :
        $dataArr = $_POST;

        unset($dataArr['back']);

        // エラーも定義しておかないと、Undefinedエラーがでる
        foreach ($dataArr as $key => $value) {
            $errArr[$key] = '';
        }

        $template = 'contact.html.twig';

        $contactArr = initMaster::getContactCategory();

    break;

    case 'complete':
        $dataArr = $_POST;

        unset($dataArr['complete']);

        $acnt->recordContact($user_id, $dataArr);
        $res = $acnt->sendMail($user_id);

        if ($res === true) {
            header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=contact');
            exit();
        } else {
            // 退会失敗時は入力画面に戻る
            $template = 'contact.html.twig';
            foreach ($dataArr as $key => $value) {
                $errArr[$key] = '';
            }
        }

    break;
}

$context = [];

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$context['contactArr'] = $contactArr;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate($template);
$template->display($context);