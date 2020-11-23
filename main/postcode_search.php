<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/postcode_search.php
 * ファイル名：postcode_search.php
 */

namespace main;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use main\Bootstrap;
use main\lib\PDODatabase;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::DB_NAME, Bootstrap::DB_TYPE);

// var_dump($_GET);
// exit;

if (isset($_GET['zip']) === true) {
    $zip = $_GET['zip'];

    $table = ' postcode ';
    $column = ' pref,city,town ';
    $where = ' zip = ? ';
    $arrVal = [$zip];

    $db->setLimitOff('1');

    $res = $db->select($table, $column, $where, $arrVal);
    // 出力結果がajaxに渡される
    echo ($res !== "" && count($res) !== 0) ? $res[0]['pref'] . $res[0]['city'] . $res[0]['town'] : '';
} else {
    echo 'no';
}