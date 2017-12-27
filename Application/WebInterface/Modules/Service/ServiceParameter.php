<?php

namespace WebInterface\Modules\Service;


use Infrastructure\PHPFormToken;
use Repositories\ServiceRepository;

class ServiceParameter
{
    function __construct()
    {
        $this->ServiceRepo = new ServiceRepository();

    }


    public function Form()
    {
        $param['id'] = '';
        $param['name'] = '';
        $param['save'] = 'Save';
        if(isset($_GET['ID']) && $_GET['ID'] > 0 ){
            $data = $this->ServiceRepo->GetById($_GET['ID']);
            $param['name'] = $data->name;
            $param['id'] = $data->id;
            $param['save'] = "Update";
        }
        return $param;
    }





}