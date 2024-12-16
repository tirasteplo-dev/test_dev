<?php

namespace App\Owen_modules;


class KTR_connect
{
    protected $actions;
    protected $module;
    public function __construct($actions, $module)
    {
        // $this->property = $data;
        $this->actions = $actions;
        $this->module = $module;
        debug($this->module);
    }
    public function getFullAnalogData()
    {
        foreach ($this->actions as $action) {
            var_dump($action);
        }
    }

    // protected function getDiscreteData()
    // {
    //     $current_row = $this->params_name_list["Float"][$regs_name];
    //     $analogParams = [];
    //     foreach ($current_row as $key => $val) {
    //         $data = $this->modbus->fc3($this->addr, $key, 4);
    //         $analogParam = round(PhpType::bytes2float(
    //             array_slice($data, 0, 4)
    //         ), 2);
    //         $analogParams += [$val => $analogParam];
    //     }
    //     return $analogParams;
    // }
    protected function getAnalogData()
    {

    }
    protected function setDiscreteData()
    {

    }
    protected function setAnalogData()
    {

    }
}