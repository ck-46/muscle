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
    public function insCartData($user_id, $item_id, $amount)
    {
        $table = ' cart ';
        $insData = [
            'user_id' => $user_id,
            'item_id' => $item_id,
            'num' => $amount
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

        // SELECT c.crt_id,i.item_id,i.item_name,SUM(i.price) AS price,SUM(num) AS num,i.price,i.image 
        // FROM cart c LEFT JOIN item i 
        // ON c.item_id = i.item_id 
        // WHERE c.user_id = 1 AND c.delete_flg =0
        // GROUP BY c.item_id ;

        // SELECT c.crt_id,i.item_id,i.item_name,SUM(i.price) AS price,SUM(num) AS num,i.image FROM cart c LEFT JOIN item i ON c.item_id = i.item_id WHERE c.user_id = 1 AND c.delete_flg =0 GROUP BY c.item_id ;
        
        $table = ' cart c LEFT JOIN item i ON c.item_id = i.item_id ';
        $column = ' c.crt_id,i.item_id,i.item_name,SUM(i.price) AS price,SUM(num) AS num,i.price,i.image ';
        $where = ' c.user_id = ? AND c.delete_flg = ? ';
        $arrVal = [$user_id, 0];

        $groupby = 'c.item_id';

        $this->db->setGroupBy($groupby);

        return $this->db->select($table, $column, $where, $arrVal);
    }

    // カート情報を削除する
    public function delCartData($del_item_id, $user_id)
    {
        $table = ' cart ';
        $insData = ['delete_flg' => 1];
        $where = ' item_id = ? AND user_id = ? ';
        $arrWhereVal = [$del_item_id, $user_id];

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

        // SELECT SUM( i.price ) AS totalPrice ";
        // FROM cart c LEFT JOIN item i ON c.item_id = i.item_id "
        // WHERE c.user_id = ? AND c.delete_flg = ? ';

        // SELECT SUM( i.price ) AS totalPrice FROM cart c LEFT JOIN item i ON c.item_id = i.item_id WHERE c.user_id = 2 AND c.delete_flg = 0 ;
        // SELECT c.crt_id,i.item_id,user_id,i.price FROM cart c LEFT JOIN item i ON c.item_id = i.item_id WHERE c.user_id = 2 AND c.delete_flg = 0 ;
        // SELECT num * price AS totalPrice FROM cart c LEFT JOIN item i ON c.item_id = i.item_id WHERE c.user_id = 2 AND c.delete_flg = 0 ;
        // SELECT num * price AS totalPrice FROM cart c LEFT JOIN item i ON c.item_id = i.item_id WHERE c.user_id = 2 AND c.delete_flg = 0 GROUP BY c.item_id;

        $table = ' cart c LEFT JOIN item i ON c.item_id = i.item_id ';
        $column = ' num * price AS totalPrice ';
        $where = ' c.user_id = ? AND c.delete_flg = ? ';
        $arrWhereVal = [$user_id, 0];

        $groupby = 'c.item_id';

        // $this->db->setGroupBy($groupby);

        $res = $this->db->select($table, $column, $where, $arrWhereVal);
        // var_dump($res);
        // exit;
        // $price = ($res !== false && count($res) !== 0) ? $res[0]['totalPrice'] : 0;
        if ($res !== false && count($res) !== 0) {
            foreach ($res as $key => $value) {
                $price[] = $value['totalPrice'];
            }
            $sumPrice = array_sum($price);
            
            // var_dump($sumPrice);
            // exit;
        } else {
            $sumPrice = '';
        }

        // exit;
        // アイテム数
        $table = ' cart c ';
        $column = ' SUM( num ) AS num ';
        $res = $this->db->select($table, $column, $where, $arrWhereVal);

        $num = ($res !== false && count($res) !== 0) ? $res[0]['num'] : 0;
        return [$num, $sumPrice];
    }
}