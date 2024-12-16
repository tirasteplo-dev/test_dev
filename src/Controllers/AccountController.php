<?php

namespace App\Controllers;

use App\Controller;
use App\Models\Account;
use Error;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDOException;
use UnexpectedValueException;

class AccountController extends Controller
{
    protected $data;
    private $account;
    const KEY = 'TIRASTEPLO_TTE';

    public function __construct()
    {
        $this->data = json_decode(file_get_contents("php://input"), TRUE);
        $this->account = new Account();
    }
    public function login()
    {
        try {
            $user = $this->account->LoginUser($this->data);
            $jwt = JWT::encode(
                array(
                    'lat' => time(),
                    "nbf" => time(),
                    'exp' => time() + 3600,
                    'data' => array(
                        'email' => $user->email,
                        'user_name' => $user->name,
                        'user_hash' => $user->hash
                    )
                ),
                self::KEY,
                'HS256'
            );
            http_response_code(200);
            echo json_encode([
                "auth_token" => $jwt,
                "email" => $user->email,
                "name" => $user->name
            ]);
        } catch (Exception $err) {
            http_response_code($err->getCode());
            echo json_encode([
                "message" => $err->getMessage(),
                "trace" => $err->getTrace()
            ]);
        }
    }
    public function confirm_auth()
    {
        try {
            $jwt = $_GET['token'];
            $auth = JWT::decode($jwt, new Key(self::KEY, 'HS256'));
            // debug($auth);
            $confirm_data = $this->account->CheckAuth($auth);
            $jwt = JWT::encode(
                array(
                    'lat' => time(),
                    "nbf" => time(),
                    'exp' => time() + 3600,
                    'data' => array(
                        'email' => $confirm_data->email,
                        'user_name' => $confirm_data->name,
                        'user_hash' => $confirm_data->user_hash
                    )
                ),
                self::KEY,
                'HS256'
            );
            http_response_code(200);
            echo json_encode([
                "auth_token" => $jwt,
                "email" => $confirm_data->email,
                "name" => $confirm_data->name
            ]);
        } catch (UnexpectedValueException $err) {
            http_response_code(403);
            echo json_encode([
                "message" => $err->getMessage(),
                "trace" => $err->getTrace()
            ]);
            // } catch (PDOException $err) {
            //     http_response_code($err->getCode());
            //     echo json_encode([
            //         "message" => $err->getMessage(),
            //         "trace" => $err->getTrace()
            //     ]);
        } catch (Exception $err) {
            http_response_code($err->getCode());
            echo json_encode([
                "message" => $err->getMessage(),
                "trace" => $err->getTrace()
            ]);
        } catch (Error $err) {
            http_response_code($err->getCode());
            echo json_encode([
                "message" => $err->getMessage(),
                "trace" => $err->getTrace()
            ]);
        }
    }
}