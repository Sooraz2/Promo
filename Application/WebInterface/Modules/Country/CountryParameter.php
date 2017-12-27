<?php

namespace WebInterface\Modules\Country;


use Repositories\CountryRepository;

class CountryParameter
{



    function __construct()
    {
        $this->CountryRepo = new CountryRepository();

    }


    public function Form()
    {
        $param['id'] = '';
        $param['name'] = '';
        $param['save'] = 'Save';
        if(isset($_GET['ID']) && $_GET['ID'] > 0 ){
            $data = $this->CountryRepo->GetById($_GET['ID']);
            $param['name'] = $data->name;
            $param['id'] = $data->id;
            $param['save'] = "Update";
        }

        return $param;
    }





}