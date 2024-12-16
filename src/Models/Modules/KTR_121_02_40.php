<?php

namespace App\Models\Modules;

use App\Model;
use Exception;
use PDOException;

class KTR_121_02_40 extends Model
{
    const TABLE_NAME = "ktr_121_02_40";

    public function __construct()
    {
        parent::__construct();
    }

    public function getAction($action)
    {
        return $this->query("SELECT * FROM " . self::TABLE_NAME . " WHERE name = :name ", ["name" => $action])->fetchObject();
    }
}