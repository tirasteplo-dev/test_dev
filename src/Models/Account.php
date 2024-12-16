<?php

namespace App\Models;
use App\Model;
use Exception;
use PDOException;

class Account extends Model
{
    const TABLE_NAME = "users";
    public $publishedYear;

    public function __construct()
    {
        // parent::__construct(self::TABLE_NAME);
        parent::__construct();
    }
    public function LoginUser($user)
    {

        $data = $this->query("SELECT * FROM " . self::TABLE_NAME . " WHERE email= :email AND password = :password", [
            "email" => $user["email"],
            "password" => $user["password"]
        ])->fetchObject();
        if (!$data) {
            throw new Exception("SUKA BLYAT NET NAHUI TEBYA DURA", 401);
        }
        $data->hash = password_hash($data->password, PASSWORD_BCRYPT);
        $this->query("UPDATE " . self::TABLE_NAME . " SET user_hash = :hash WHERE id= :id", ['hash' => $data->hash, 'id' => $data->id]);
        // debug($hash);
        return $data;
    }
    public function CheckAuth($auth)
    {
        try {
            $confirm_hash = $this->query("SELECT * FROM " . self::TABLE_NAME . " WHERE user_hash= :hash", ["hash" => $auth->data->user_hash])->fetchObject();
            if (!$confirm_hash) {
                throw new Exception("ERROR LOGIN CHECK UEBA", 401);
            }
            return $confirm_hash;
        } catch (PDOException $err) {
            throw new PDOException($err->getMessage());
        }
    }
}