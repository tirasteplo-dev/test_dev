<?php
namespace App\Controllers;
use App\Controller;
use App\Models\Heatbase;
use Exception;

class KotelnayaController extends Controller
{
    const OWEN_ROUTE = "App\\Owen_modules\\";
    protected $data;
    protected $heatbase_model;

    protected $module;

    public function __construct()
    {
        // debug($_SERVER['REQUEST_METHOD']);
        $this->heatbase_model = new Heatbase();
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET': {
                $this->data = $_GET;
                break;
            }
            case 'POST': {
                $this->data = json_decode(file_get_contents("php://input"), TRUE);
                break;
            }
            default: {
                $ex = new Exception("METHOD NOT ALLOWED", 403);
                http_response_code($ex->getCode());
                echo json_encode([
                    "message" => $ex->getMessage(),
                    "trace" => $ex->getTrace(),
                ]);
                break;
            }
        }
    }
    public function awailable_requests()
    {
        $this->getModule($this->data['id']);
        $this->module->getAwailableActions();

    }
    private function getModule($id)
    {
        $module_db = $this->heatbase_model->getHeatbase($id);
        $class = self::OWEN_ROUTE . $module_db->module_name . "_Controller";
        $this->module = new $class($module_db);
    }
    public function get_current_settings()
    {
        $module_db = $this->heatbase_model->getHeatbase($this->data['module_id']);
        $class = self::OWEN_ROUTE . $module_db->module_name . "_Controller";
        $module = new $class($module_db);
        $module->getCurrentSettings();
    }
}