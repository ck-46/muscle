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

    // 全商品リストを取得する
    public function getAllList($start)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price, image ';
        $where = '';
        $arrVal = [];

        $item_num = $this->getItemNum($table, $col, $where, $arrVal);
        // var_dump($item_num);
        // exit;
        $dataArr = $this->getLimitDataArr($table, $col, $where, $arrVal, $start);

        return [$item_num, $dataArr];
    }

     // カテゴリー別リストを取得する
    public function getCategoryList($category_key, $category_flg, $start)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price, image ';
        if ($category_flg === 'flavor') {
            $where = ' flavor_id = ? ';
        } elseif ($category_flg === 'purpose') {
            $where = ' purpose_id = ? ';
        } elseif ($category_flg === 'brand') {
            $where = ' brand_id = ? ';
        } else {
            $where = '';
        }
        $arrVal = ($category_key !== '') ? [$category_key] : [];

        $item_num = $this->getItemNum($table, $col, $where, $arrVal);
        $dataArr = $this->getLimitDataArr($table, $col, $where, $arrVal, $start);

        return [$item_num, $dataArr];
    }

    private function getItemNum($table, $col, $where, $arrVal)
    {
        $res = $this->db->select($table, $col, $where, $arrVal);
        $item_num = count($res);

        return $item_num;
    }

    private function getLimitDataArr($table, $col, $where, $arrVal, $start)
    {
        $limit = $start . ' ,12 ';
        $this->db->setLimitOff($limit);
        $res = $this->db->select($table, $col, $where, $arrVal);
        $dataArr = ($res !== false && count($res) !== 0) ? $res : false;

        return $dataArr;
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

    public function getAmount()
    {
        for ($i = 0; $i < 100; $i ++) {
            $amountArr[] = $i;
        }

        return $amountArr;
    }

    public function getChangeAmount()
    {
        for ($i = 1; $i < 100; $i ++) {
            $amountArr[] = $i;
        }

        return $amountArr;
    }

    public function getSearchResult($keywords, $start)
    {
        $table = ' item ';
        $col = ' item_id, item_name, price, image ';
        $where = [];
        $arrVal = [];

        if ($keywords !== '') {
            $keywords = explode(' ', $keywords);
            foreach ($keywords as $keyword) {
                $where[] = ' item_name LIKE ? OR detail LIKE ? ';
                $keyword = '%' . $keyword . '%';
                array_push($arrVal, $keyword, $keyword);
            }
            $where = implode(' OR ', $where);
        }

        $item_num = $this->getItemNum($table, $col, $where, $arrVal);
        $dataArr = $this->getLimitDataArr($table, $col, $where, $arrVal, $start);

        return [$item_num, $dataArr];
    }

    public function getTopThree()
    {
        // $table = ' sold_item ';
        // $col = ' item_id, item_name, detail, price, image ';
        // $where = ($item_id !== '') ? ' item_id = ? ' : '';
        // $arrVal = ($item_id !== '') ? [$item_id] : [];

        // SELECT SUM( num ) AS total, i.item_id, i.item_name, i.image
        // FROM sold_item s LEFT JOIN item i ON s.item_id = i.item_id
        // GROUP BY i.item_id ORDER BY total DESC LIMIT 3
        // SELECT SUM( num ) AS total, i.item_id, i.item_name, i.image FROM sold_item s LEFT JOIN item i ON s.item_id = i.item_id GROUP BY i.item_id ORDER BY total DESC LIMIT 3;

        $table = ' sold_item s LEFT JOIN item i ON s.item_id = i.item_id ';
        $column = ' SUM( num ) AS total, i.item_id, i.item_name, i.image ';

        $orderby = ' total DESC ';
        $limit = ' 3 ';
        $groupby = ' i.item_id ';

        $this->db->setOrder($orderby);
        $this->db->setLimitOff($limit);
        $this->db->setGroupBy($groupby);

        $res = $this->db->select($table, $column);

        return ($res !== false && count($res) !== 0) ? $res : false;
    }


}