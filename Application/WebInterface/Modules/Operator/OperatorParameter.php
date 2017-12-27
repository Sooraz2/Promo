<?php

namespace WebInterface\Modules\Operator;


use Repositories\OperatorRepository;

class OperatorParameter
{


    function __construct()
    {
        $this->OperatorRepo = new OperatorRepository();

    }

    public function Form()
    {
        $param['id'] = '';
        $param['name'] = '';
        $param['save'] = 'Save';
        if(isset($_GET['ID']) && $_GET['ID'] > 0 ){
            $data = $this->OperatorRepo->GetById($_GET['ID']);
            $param['name'] = $data->name;
            $param['id'] = $data->id;
            $param['save'] = "Update";
        }

        return $param;
    }





}