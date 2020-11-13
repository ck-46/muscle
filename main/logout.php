<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/logout.php
 * ファイル名：logout.php（ログアウトをするプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/logout.php
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

//セッションの中身をすべて削除
$_SESSION = array();
//セッションを破壊
session_destroy();

header('Location: ' . Bootstrap::ENTRY_URL . 'complete.php?key=logout');
exit();