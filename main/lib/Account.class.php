<?php
/**
 * ファイルパス：/Applications/XAMPP/xamppfiles/htdocs/muscle/main/lib/Account.class.php
 * ファイル名：Account.class.php
 */

namespace main\lib;

class Account
{
    public $db = null;
    public $ses = null;

    private $dataArr = [];
    private $errArr = [];

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function errorCheck($dataArr)
    {
        $this->dataArr = $dataArr;
        $this->createErrorMessage();

        $this->familyNameCheck();
        $this->firstNameCheck();
        $this->mailCheck();
        $this->confirmMail();
        $this->passwordCheck();
        $this->confirmPassword();

        return $this->errArr;
    }

    private function createErrorMessage()
    {
        foreach ($this->dataArr as $key => $val) {
            $this->errArr[$key] = '';
        }
    }

    private function familyNameCheck()
    {
        if ($this->dataArr['family_name'] === '') {
            $this->errArr['family_name'] = '名前（氏）を入力してください';
        }
    }

    private function firstNameCheck()
    {
        if ($this->dataArr['first_name'] === '') {
            $this->errArr['first_name'] = '名前（名）を入力してください';
        }
    }

    private function mailCheck()
    {
        if ($this->dataArr['email'] === '') {
            $this->errArr['email'] = 'メールアドレスをを入力してください';
        } elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/', $this->dataArr['email']) === 0) {
            $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください';
        } else {
            $table = ' member ';
            $col = ' email ';
            $where = ' email = ? ';
            $arrVal = [$this->dataArr['email']];

            $res = $this->db->select($table, $col, $where, $arrVal);
            // var_dump($res);
            // exit;

            if (count($res) !== 0) {
                if ($res[0]['email'] === $this->dataArr['email']) {
                    $this->errArr['email'] = '既に同じメールアドレスが存在します';
                }
            }
        }
    }

    private function confirmMail()
    {
        if ($this->dataArr['confirm_email'] === '') {
            $this->errArr['confirm_email'] = 'メールアドレスをを入力してください';
        } elseif ($this->dataArr['email'] !== $this->dataArr['confirm_email']) {
            $this->errArr['confirm_email'] = 'メールアドレスが一致しません';
        }
    }

    private function passwordCheck(){
        if ($this->dataArr['password'] === '') {
            $this->errArr['password'] = 'パスワードを入力してください';
        } elseif (preg_match('/^[0-9a-zA-Z]{6,20}$/', $this->dataArr['password']) === 0) {
            $this->errArr['password'] = 'パスワードを正しい形式でで入力してください';
        }
    }

    private function confirmPassword()
    {
        if ($this->dataArr['confirm_password'] === '') {
            $this->errArr['confirm_password'] = 'パスワードを入力してください';
        } elseif ($this->dataArr['password'] !== $this->dataArr['confirm_password']) {
            $this->errArr['confirm_password'] = 'パスワードが一致しません';
        }
    }

    public function getErrorFlg()
    {
        $err_check = true;
        foreach ($this->errArr as $key => $value) {
            if ($value !== '') {
                $err_check = false;
            }
        }
        return $err_check;
    }

    public function insMemberData($dataArr)
    {
        $table = ' member ';
        $insData = $dataArr;

        $res = $this->db->insert($table, $insData);
        return $res;
    }

    private function loginErrorCheck()
    {
        if ($this->dataArr['email'] === '' || $this->dataArr['password'] === '') {
            $this->errArr['login'] = 'メールアドレスとパスワードを入力してください';
        } else {
            $table = ' member ';
            $col = ' * ';
            $where = ' email = ? ';
            $arrVal = [$this->dataArr['email']];

            $res = $this->db->select($table, $col, $where, $arrVal);

            if (count($res) === 0) {
                $this->errArr['login'] = 'メールアドレスまたはパスワードが異なります';
            } elseif (password_verify($this->dataArr['password'], $res[0]['password']) !== true) {
                $this->errArr['login'] = 'メールアドレスまたはパスワードが異なります';
            } elseif ($res[0]['delete_flg'] === '1') {
                $this->errArr['login'] = 'こちらのアカウントは既に退会しております';
            }
        }
    }

    public function loginCheck($dataArr)
    {
        $this->errArr['login'] = '';
        $this->dataArr = $dataArr;

        $this->loginErrorCheck();

        return $this->errArr;
    }

    public function deleteCheck($dataArr)
    {
        $this->dataArr = $dataArr;
        // クラス内のメソッドを読み込む
        $this->createErrorMessage();

        $this->delCtgCheck();
        $this->delTextCheck();

        return $this->errArr;
    }

    private function delCtgCheck()
    {
        if ($this->dataArr['del_ctg_id'] === '') {
            $this->errArr['del_ctg_id'] = '退会理由を選択してください';
        }
    }

    private function delTextCheck()
    {
        if ($this->dataArr['del_text'] === '') {
            $this->errArr['del_text'] = '退会理由の入力をお願いします';
        }
    }

    public function delMemberData($mem_id, $dataArr)
    {
        // delete_flgを1にする
        $date = date('Y-m-d H:i:s');
        $table = ' member ';
        $insData = [
            'delete_flg' => 1,
            'delete_date' => $date
        ];
        $where = 'mem_id = ' . $mem_id;

        $res = $this->db->update($table, $insData, $where);
        return $res;
    }

    public function recordDelReason($mem_id, $dataArr)
    {
        // 退会理由をDBに格納
        $dataArr['mem_id'] = $mem_id;
        $dataArr['delete_date'] = date('Y-m-d H:i:s');

        $table = ' delete_reason ';
        $insData = $dataArr;

        $result = $this->db->insert($table, $insData);
        return $result;
    }
}