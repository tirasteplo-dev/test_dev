<?php
namespace App\Models;

use App\Model;

class Heatbase extends Model
{
    const TABLE_NAME = "heatbases";

    function __construct()
    {
        parent::__construct();
    }

    public function getHeatbase($id)
    {
        return $this->query(
            "SELECT " . self::TABLE_NAME . ".*, modules.module_name, modules.module_type 
            FROM " . self::TABLE_NAME . " 
            INNER JOIN modules 
            ON " . self::TABLE_NAME . ".module_id = modules.id 
            WHERE `disp.id`=:id",
            ["id" => $id]
        )->fetchObject();
    }
}