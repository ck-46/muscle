<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/lib/Item.class.php
 * ファイル名：Item.class.php（商品に関するプログラムのクラスファイル、Model）
 */

namespace main\lib;

class Item
{

    public $cateArr = [];
    public $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }
    // カテゴリーリストの取得
    public function getCategoryList()
    {
        $table = ' category ';
        $col = ' ctg_id, category_name ';
        $res = $this->db->select($table, $col);
        return $res;
    }

    // 全商品リストを取得する
    public function getAllList()
    {
        $table = ' item ';
        $col = ' item_id, item_name, price, image ';
        $where = '';
        $arrVal = [];

        $res = $this->db->select($table, $col, $where, $arrVal);

        return ($res !== false && count($res) !== 0) ? $res : false;
    }

    // フレーバー別リストを取得する
    public function getFlavorList($flavor_id)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price, image ';
        $where = ($flavor_id !==  '') ? ' flavor_id = ? ' : '';
        $arrVal = ($flavor_id !== '') ? [$flavor_id] : [];

        $res = $this->db->select($table, $col, $where, $arrVal);

        return ($res !== false && count($res) !== 0) ? $res : false;
    }
    
    // 目的別リストを取得する
    public function getPurposeList($purpose_id)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price, image ';
        $where = ($purpose_id !==  '') ? ' purpose_id = ? ' : '';
        $arrVal = ($purpose_id !== '') ? [$purpose_id] : [];

        $res = $this->db->select($table, $col, $where, $arrVal);

        return ($res !== false && count($res) !== 0) ? $res : false;
    }

    // ブランド別リストを取得する
    public function getBrandList($brand_id)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price, image ';
        $where = ($brand_id !==  '') ? ' brand_id = ? ' : '';
        $arrVal = ($brand_id !== '') ? [$brand_id] : [];

        $res = $this->db->select($table, $col, $where, $arrVal);

        return ($res !== false && count($res) !== 0) ? $res : false;
    }

    // 商品の詳細情報を取得する
    public function getItemDetailData($item_id)
    {
        $table = ' item ';
        $col = ' item_id, item_name, detail, price, image ';
        $where = ($item_id !== '') ? ' item_id = ? ' : '';
        $arrVal = ($item_id !== '') ? [$item_id] : [];

        $res = $this->db->select($table, $col, $where, $arrVal);
        return ($res !== false && count($res) !== 0) ? $res : false;
    }
}