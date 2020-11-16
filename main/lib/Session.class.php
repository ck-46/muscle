<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/lib/Session.class.php
 * ファイル名：Session.class.php（セッション関係のクラスファイル、Model）
 */

namespace main\lib;

class Session
{

    public $session_key = '';
    public $db = null;

    public function __construct($db)
    {
        // セッションをスタートする
        session_start();
        // セッションIDを取得する
        // $this->session_key = session_id();
        // DBの登録
        $this->db = $db;
    }

    public function getSession($dataArr)
    {
        $table = ' user ';
        $col = ' * ';
        $where = ' email = ? ';
        $arrVal = [$dataArr['email']];

        $res = $this->db->select($table, $col, $where, $arrVal);

        if (count($res) !== 0) {
            $_SESSION['user_id'] = $res[0]['user_id'];
            $_SESSION['user_name'] = $res[0]['family_name'] . ' ' . $res[0]['first_name'];

            return $_SESSION;
        }
    }
}