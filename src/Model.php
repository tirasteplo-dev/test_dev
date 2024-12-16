<?php

namespace App;

use PDOException;
use PDO;

define("DB_NAME", $_ENV['DB_NAME']);
class Model
{
    protected $db;
    public function __construct($type = DB_NAME)
    {
        // debug($_ENV);
        $this->db = new PDO(
            'mysql:host=' . $_ENV["DB_HOST"] . ';dbname=' . $type . '',
            $_ENV["DB_USER"],
            $_ENV["DB_PASS"],
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            )
        );
    }
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            foreach ($params as $key => $val) {
                $par = "/:" . "$key/";
                if (preg_match($par, $sql)) {
                    $stmt->bindValue(':' . $key, $val);
                }
            }
        }
        // debug($stmt);
        $stmt->execute();
        return $stmt;
    }
    public function row($sql, $params = [])
    {
        try {
            // var_dump($sql);
            $result = $this->query($sql, $params);
        } catch (PDOException $e) {
            return $e;
        }
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    public function column($sql, $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }
}
