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

    // public function checkSession()
    // {
    //     // セッションのIDのチェック
    //     $customer_no = $this->selectSession();
    //     // セッションIDがある（過去にカートに来たことがある）
    //     if ($customer_no !== false) {
    //         $_SESSION['customer_no'] = $customer_no;
    //     } else {
    //         // セッションIDがない（初めてこのサイトに来ている）
    //         $res = $this->insertSession();
    //         if ($res === true) {
    //             $_SESSION['customer_no'] = $this->db->getLastId();
    //         } else {
    //             $_SESSION['customer_no'] = '';
    //         }
    //     }
    // }

    // private function selectSession()
    // {
    //     $table = ' session ';
    //     $col = ' customer_no ';
    //     $where = ' session_key = ? ';
    //     $arrVal = [$this->session_key];

    //     $res = $this->db->select($table, $col, $where, $arrVal);
    //     return (count($res) !== 0) ? $res[0]['customer_no'] : false;
    // }

    // private function insertSession()
    // {
    //     $table = ' session ';
    //     $insData = ['session_key' => $this->session_key];
    //     $res = $this->db->insert($table, $insData);
    //     return $res;
    // }

    public function getSession($dataArr)
    {
        $table = ' member ';
        $col = ' * ';
        $where = ' email = ? ';
        $arrVal = [$dataArr['email']];

        $res = $this->db->select($table, $col, $where, $arrVal);

        if (count($res) !== 0) {
            $_SESSION['mem_id'] = $res[0]['mem_id'];
            $_SESSION['name'] = $res[0]['family_name'] . ' ' . $res[0]['first_name'];

            return $_SESSION;
        }
    }
}