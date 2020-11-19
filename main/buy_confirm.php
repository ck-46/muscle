<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/buy_confirm.php
 * ファイル名：buy_confirm.php（入力情報を確認するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/buy_confirm.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Cart;
use main\lib\Account;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$acnt = new Account($db);
$cart = new Cart($db);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$user_id = (isset($_SESSION['user_id']) === true) ? $_SESSION['user_id'] : '';

// 戻るから来た場合
if (isset($_POST['back']) === true) {
    $mode = 'back';
}
// 登録完了
if (isset($_POST['complete']) === true) {
    $mode = 'complete';
}

switch ($mode) {
    case 'back' :
        header('Location: ' . Bootstrap::ENTRY_URL . 'cart.php');
        exit();
    break;

    case 'complete':
        $dataArr = $cart->getCartData($user_id);

        foreach ($dataArr as $key => $value) {
            $item_id_arr[] = $value['item_id'];
            $num_arr[] = $value['num'];
        }

        // var_dump($item_id_arr);
        // exit;

        // 購入されたカートの商品のdelete_flgを更新
        foreach ($item_id_arr as $key) {
            // var_dump($key);
            // exit;
            $cart->delCartData($key, $user_id);
        }

        $item_id = implode(',', $item_id_arr);
        $num = implode(',', $num_arr);

        // var_dump($item_id);
        // var_dump($num);
        // exit;

        $res = $cart->insSoldItemData($user_id, $item_id, $num);

        if ($res === true) {
            // 購入完了メール送りたい
            // 登録成功時は完成ページへ
            header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=buy');
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