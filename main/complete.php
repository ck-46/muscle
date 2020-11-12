<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/complete.php
 * ファイル名：complete.php（完了ページを表示するプログラム、Controller）
 * アクセスURL：http://localhost/muscle/main/complete.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;

// テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$template = $twig->loadTemplate('complete.html.twig');
$template->display([]);