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

$category_flg = '';
$category_key = '';
$keywords = '';

// GETで現在のページ数を取得する（未入力の場合は1を挿入）
if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
} else {
    $page = 1;
}

// スタートのポジションを計算する
if ($page > 1) {
    // 例：２ページ目の場合は、『(2 × 12) - 12 = 12』
    $start = ($page * 12) - 12;
} else {
    $start = 0;
}

// フレーバー別リスト
if (isset($_GET['flavor']) === true && preg_match('/^[0-9]+$/', $_GET['flavor']) === 1) {
    $category_key = $_GET['flavor'];
    $category_flg = 'flavor';
}
// 目的別リスト
if (isset($_GET['purpose']) === true && preg_match('/^[0-9]+$/', $_GET['purpose']) === 1) {
    $category_key = $_GET['purpose'];
    $category_flg = 'purpose';
}
// ブランド別リスト
if (isset($_GET['brand']) === true && preg_match('/^[0-9]+$/', $_GET['brand']) === 1) {
    $category_key = $_GET['brand'];
    $category_flg = 'brand';
}

if ($category_flg === 'flavor' || $category_flg === 'purpose' || $category_flg === 'brand') {
    list($item_num, $dataArr) = $itm->getCategoryList($category_key, $category_flg, $start);
}


// 検索結果
$searchFalse = '';

if (isset($_POST['keywords']) === true && $_POST['keywords'] !== '') {
    $category_flg = 'keywords';
    $category_key = mb_convert_kana($_POST['keywords'], 's', 'UTF-8');
} elseif (isset($_GET['keywords']) === true && $_GET['keywords'] !== '') {
    $category_flg = 'keywords';
    $category_key = mb_convert_kana($_GET['keywords'], 's', 'UTF-8');
}

if ($category_flg === 'keywords' && $category_key !== '') {
    list($item_num, $dataArr) = $itm->getSearchResult($category_key, $start);
    if ($dataArr === false) $searchFalse = '「' . $category_key . '」の検索結果は見つかりませんでした';
}

// 全商品リスト
if ($category_key === '') {
    list($item_num, $dataArr) = $itm->getAllList($start);
}

// ページネーションの数を取得する
$pagination = ceil( $item_num / 12 );

$context = [];

$context['dataArr'] = $dataArr;
$context['searchFalse'] = $searchFalse;
$context['category_flg'] = $category_flg;
$context['category_key'] = $category_key;
$context['keywords'] = $keywords;
$context['pagination'] = $pagination;
$context['page'] = $page;

$context['user_name'] = $user_name;
$template = $twig->loadTemplate('list.html.twig');
$template->display($context);