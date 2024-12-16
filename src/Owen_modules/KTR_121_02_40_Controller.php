<?php

namespace App\Owen_modules;

use \App\Models\Modules\KTR_121_02_40;
use \App\ModbusAlgorithms;

use stdClass;


class KTR_121_02_40_Controller
{
    const AWAILABLE_ACTIONS = [
        "cmd_Start",
        "ua_Twd",
        "ua_Twd_LWL",
        "ua_Twd_HWL",
        "lv_Twd_LWL",
        "lv_Twd_HWL",
        "net_Start",
        "net_Stop"
    ];
    private $ktr_model;
    protected $heatbase;
    protected $modbus_algorithms;
    public function __construct($module)
    {
        // parent::__construct();
        // dd($module);
        $this->heatbase = new stdClass;
        $this->heatbase->module = $module;
        $this->ktr_model = new KTR_121_02_40;
        $this->heatbase->actions = array();
        foreach (self::AWAILABLE_ACTIONS as $action) {
            $action = $this->ktr_model->getAction($action);
            $this->heatbase->actions[$action->name] = $action;
        }
        $this->modbus_algorithms = new ModbusAlgorithms($this->heatbase->module);
    }

    public function getAwailableActions()
    {
        echo json_encode($this->heatbase);
    }

    public function getCurrentSettings()
    {
        foreach ($this->heatbase->actions as $action) {
            if ($action->access === "RW" || $action->access === "R") {
                if ($action->data_type === "word" || $action->data_type === "real") {
                    $this->modbus_algorithms->getAnalogData($action);
                } else {
                    $this->modbus_algorithms->getDiscreteData($action);
                }
            }
        }
    }
}