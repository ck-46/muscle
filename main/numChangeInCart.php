<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/numChangeInCart.php
 * ファイル名：numChangeInCart.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Cart;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$cart = new Cart($db);

if (isset($_POST) === true) {
    $num = $_POST['changed_num'];
    $item_id = $_POST['item_id'];
    $user_id = $_POST['user_id'];

    try {
        $res = $cart->delCartData($item_id, $user_id);

        if ($res === true) {
            $cart->insCartData($user_id, $item_id, $num);

            list($sumNum, $sumPrice) = $cart->getItemAndSumPrice($user_id);

            echo 'カート内商品数：' . $sumNum . '個<br>合計金額：&yen;' . number_format($sumPrice, 0, '.', ',');
        }

    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}
exit;