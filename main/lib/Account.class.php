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
    private $updateDataArr = [];
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
        $this->zipCheck();
        $this->addressCheck();
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

    private function zipCheck()
    {
        if ($this->dataArr['zip'] === ''){
            $this->errArr['zip'] = '郵便番号を入力してください';
        } elseif (preg_match('/^[0-9]{7}$/', $this->dataArr['zip']) === 0) {
            $this->errArr['zip'] = '郵便番号は7文字の半角数字で入力してください';
        }
    }

    private function addressCheck()
    {
        if ($this->dataArr['address'] === '') {
            $this->errArr['address'] = '住所を入力してください';
        }
    }

    private function mailCheck()
    {
        if ($this->dataArr['email'] === '') {
            $this->errArr['email'] = 'メールアドレスをを入力してください';
        } elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/', $this->dataArr['email']) === 0) {
            $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください';
        } else {
            $table = ' user ';
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

    public function insUserData($dataArr)
    {
        $table = ' user ';
        $insData = $dataArr;

        $res = $this->db->insert($table, $insData);
        return $res;
    }

    private function loginErrorCheck()
    {
        if ($this->dataArr['email'] === '' || $this->dataArr['password'] === '') {
            $this->errArr['login'] = 'メールアドレスとパスワードを入力してください';
        } else {
            $table = ' user ';
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

    public function delUserData($user_id, $dataArr)
    {
        // delete_flgを1にする
        $date = date('Y-m-d H:i:s');
        $table = ' user ';
        $insData = [
            'delete_flg' => 1,
            'delete_date' => $date
        ];
        $where = 'user_id = ' . $user_id;

        $res = $this->db->update($table, $insData, $where);
        return $res;
    }

    public function recordDelReason($user_id, $dataArr)
    {
        // 退会理由をDBに格納
        $dataArr['user_id'] = $user_id;

        $table = ' delete_reason ';
        $insData = $dataArr;

        $result = $this->db->insert($table, $insData);
        return $result;
    }

    public function getUserData($user_id)
    {
        $table = ' user ';
        $col = 'family_name,first_name,email,password,zip,address';
        $where = ' user_id = ? ';
        $arrVal = [$user_id];

        $res = $this->db->select($table, $col, $where, $arrVal);
        // var_dump($res);
        // exit;
        $dataArr = $res[0];
        return $dataArr;
    }

    public function updateCheck($updateDataArr)
    {
        $this->updateDataArr = $updateDataArr;

        if ($this->updateDataArr['family_name'] === '' &&
            $this->updateDataArr['first_name'] === '' &&
            $this->updateDataArr['email'] === '' &&
            $this->updateDataArr['password'] === '') {

            $this->errArr = ['all' => '変更がありません'];
        }

        if ($this->updateDataArr['email'] !== '' && preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/', $this->updateDataArr['email']) === 0) {
            $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください';
        }
        if ($this->updateDataArr['password'] !== '' && preg_match('/^[0-9a-zA-Z]{6,20}$/', $this->updateDataArr['password']) === 0) {
            $this->errArr['password'] = 'パスワードを正しい形式でで入力してください';
        }
        return $this->errArr;
    }

    public function updateUserData($updateDataArr, $user_id)
    {
        $this->updateDataArr = $updateDataArr;
        // var_dump($this->updateDataArr);
        // exit;
        $table = ' user ';
        foreach ($this->updateDataArr as $key => $value) {
            if ($value !== '') {
                $insData[$key] = $value;
            }
        }
        $date = date('Y-m-d H:i:s');
        $insData['update_date'] = $date;
        $where = 'user_id = ' . $user_id;

        $res = $this->db->update($table, $insData, $where);
        return $res;
    }

    public function contactCheck($dataArr)
    {
        $this->dataArr = $dataArr;
        // クラス内のメソッドを読み込む
        $this->createErrorMessage();

        $this->contactCtgCheck();
        $this->contactContentCheck();

        return $this->errArr;
    }

    private function contactCtgCheck()
    {
        if ($this->dataArr['contact_ctg_id'] === '') {
            $this->errArr['contact_ctg_id'] = '種別を選択してください';
        }
    }

    private function contactContentCheck()
    {
        if ($this->dataArr['content'] === '') {
            $this->errArr['content'] = 'お問い合わせ内容の入力をお願いします';
        }
    }

    public function recordContact($user_id, $dataArr)
    {
        // お問い合わせ内容をDBに格納
        $dataArr['user_id'] = $user_id;

        $table = ' contact ';
        $insData = $dataArr;

        $result = $this->db->insert($table, $insData);
        return $result;
    }

    public function sendMail($user_id)
    {
        $userData = $this->getUserData($user_id);

        $name = $userData['family_name'] . $userData['first_name'];
        $email = $userData['email'];
        $mailHeader = "From: chibakohei@gmail.com";
        $mailSubject = "お問い合わせありがとうございます";
        $mailBody = $name . "様 お問い合わせありがとうございます";

        mb_language( 'Japanese' );
        mb_internal_encoding( 'UTF-8' );
        $res = mb_send_mail($email, $mailSubject, $mailBody, $mailHeader, '-f' . 'chibakohei@gmail.com');

        return $res;
    }
}

// Notice: A non well formed numeric value encountered in /Applications/XAMPP/xamppfiles/htdocs/muscle/vendor/twig/twig/src/Environment.php(497) : eval()'d code on line 77