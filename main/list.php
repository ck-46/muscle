<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/list.php
 * ファイル名：list.php（商品一覧の表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/list.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;
use main\lib\Session;
use main\lib\Item;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);
$ses = new Session($db);
$user_name = (isset($_SESSION['user_name']) === true) ? $_SESSION['user_name'] : '';
$itm = new Item($db);

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$flavor_id = '';
$purpose_id = '';
$brand_id = '';
$keywords = '';

// フレーバー別リスト
if (isset($_GET['flavor']) === true && preg_match('/^[0-9]+$/', $_GET['flavor']) === 1) {
    $flavor_id = $_GET['flavor'];
    $dataArr = $itm->getFlavorList($flavor_id);
}
// 目的別リスト
if (isset($_GET['purpose']) === true && preg_match('/^[0-9]+$/', $_GET['purpose']) === 1) {
    $purpose_id = $_GET['purpose'];
    $dataArr = $itm->getPurposeList($purpose_id);
}
// ブランド別リスト
if (isset($_GET['brand']) === true && preg_match('/^[0-9]+$/', $_GET['brand']) === 1) {
    $brand_id = $_GET['brand'];
    $dataArr = $itm->getBrandList($brand_id);
}

// 検索結果
$searchFalse = '';

// var_dump($_POST);

if (isset($_POST['keywords']) === true && $_POST['keywords'] !== '') {
    $keywords = mb_convert_kana( $_POST['keywords'], 's', 'UTF-8');
} elseif (isset($_GET['keywords']) === true && $_GET['keywords'] !== '') {
    $keywords = mb_convert_kana( $_GET['keywords'], 's', 'UTF-8');
}

if ($keywords !== '') {
    $dataArr = $itm->getSearchResult($keywords);

    if ($dataArr === false) $searchFalse = '「' . $keywords . '」の検索結果は見つかりませんでした';
}

// var_dump($searchFalse);
// exit;
// 全商品リスト
if ($flavor_id === '' && $purpose_id === '' && $brand_id === '' && $keywords === '') {
    $dataArr = $itm->getAllList();
}

$context = [];

$context['dataArr'] = $dataArr;
$context['searchFalse'] = $searchFalse;
$context['keywords'] = $keywords;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('list.html.twig');
$template->display($context);