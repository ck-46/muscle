<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/lib/Cart.class.php
 * ファイル名：Cart.class.php（カートに関するプログラムのクラスファイル、Model）
 */

namespace main\lib;

class Cart
{
    private $db = null;

    public function __construct($db = null)
    {
        $this->db = $db;
    }

    // カートに登録する（必要な情報は、誰が$customer_no、何を($item_id)）
    public function insCartData($user_id, $item_id)
    {
        $table = ' cart ';
        $insData = [
            'user_id' => $user_id,
            'item_id' => $item_id
        ];
        return $this->db->insert($table, $insData);
    }

    // カートの情報を取得する（必要な情報は、誰が$customer_no。必要な商品情報は名前、商品画像、金額）
    public function getCartData($user_id)
    {
        // SELECT
        // c.crt_id,
        // i.item_id,
        // i.item_name,
        // i.price,
        // i.image
        // FROM
        // cart c
        // LEFT JOIN
        // item i
        // ON
        // c.item_id = i.item_id
        // WHERE
        // c.user_id = ? AND c.delete_flg = ? ;
        $table = ' cart c LEFT JOIN item i ON c.item_id = i.item_id ';
        $column = ' c.crt_id, i.item_id, i.item_name, i.price, i.image ';
        $where = ' c.user_id = ? AND c.delete_flg = ? ';
        $arrVal = [$user_id, 0];

        return $this->db->select($table, $column, $where, $arrVal);
    }

    // カート情報を削除する：必要な情報はどのカートを($crt_id)
    public function delCartData($crt_id)
    {
        $table = ' cart ';
        $insData = ['delete_flg' => 1];
        $where = ' crt_id = ? ';
        $arrWhereVal = [$crt_id];

        return $this->db->update($table, $insData, $where, $arrWhereVal);
    }

    // アイテム数と合計金額を取得する
    public function getItemAndSumPrice($user_id)
    {
        // 合計金額
        // SELECT
        // SUM( i.price ) AS totalPrice ";
        // FROM
        // cart c
        // LEFT JOIN
        // item i
        // ON
        // c.item_id = i.item_id "
        // WHERE
        // c.user_id = ? AND c.delete_flg = ? ';
        $table = ' cart c LEFT JOIN item i ON c.item_id = i.item_id ';
        $column = ' SUM( i.price ) AS totalPrice ';
        $where = ' c.user_id = ? AND c.delete_flg = ? ';
        $arrWhereVal = [$user_id, 0];

        $res = $this->db->select($table, $column, $where, $arrWhereVal);
        $price = ($res !== false && count($res) !== 0) ? $res[0]['totalPrice'] : 0;

        // アイテム数
        $table = ' cart c ';
        $column = ' SUM( num ) AS num ';
        $res = $this->db->select($table, $column, $where, $arrWhereVal);

        $num = ($res !== false && count($res) !== 0) ? $res[0]['num'] : 0;
        return [$num, $price];
    }
}