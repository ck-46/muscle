<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/master/initMaster.class.php
 * ファイル名：initMaster.class.php
 */

namespace main\master;

class initMaster
{

    public static function getDeleteCategory()
    {
        $delArr = [
            '1' => '会員ID・パスワードを忘れた',
            '2' => '二重に会員登録してしまった',
            '3' => '品揃えに満足できなかった',
            '4' => '商品情報に満足できなかった',
            '5' => '価格に満足できなかった',
            '6' => '配達・お届け商品に満足できなかった',
            '7' => '接客・応対に満足できなかった',
            '8' => 'サービスに満足できなかった',
            '9' => 'その他'
        ];

        return $delArr;
    }
}