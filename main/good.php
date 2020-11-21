<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/good_toggle.php
 * ファイル名：good_toggle.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Account;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$acnt = new Account($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

// var_dump($_GET);
// var_dump($_POST);
// exit;

if (isset($_POST) === true) {
    $user_id = $_POST['user_id'];
    $review_id = $_POST['review_id'];


// if (isset($_GET) === true) {
//     $user_id = $_GET['user_id'];
//     $review_id = $_GET['review_id'];
//     $item_id = $_GET['item_id'];

    // var_dump($user_id);
    // var_dump($review_id);
    // exit;
    
    try {
        $goodData = $acnt->isGood($user_id, $review_id);

        // var_dump($goodData);
        // exit;
        // var_dump(count($goodData));
        // exit;

        if (count($goodData) === 0) {
            $acnt->insGood($user_id, $review_id);

            // var_dump($acnt->insGood($user_id, $review_id));
            // exit;
            // var_dump(count($acnt->countGood($review_id)));
            // exit;
            echo count($acnt->countGood($review_id));
        } else {
            $acnt->delGood($user_id, $review_id);
            echo count($acnt->countGood($review_id));
        }

        // header('Location: ' . Bootstrap::ENTRY_URL . 'detail.php?item_id=' . $item_id);
        //         exit();
    
        // var_dump($user_id);
        // var_dump($review_id);
        // var_dump($goodData);
        // exit;

    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }

}
