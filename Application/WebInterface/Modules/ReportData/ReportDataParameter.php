<?php

namespace WebInterface\Modules\ReportData;


use Repositories\CountryRepository;
use Infrastructure\PHPFormToken;
class ReportDataParameter
{


    function __construct()
    {

        $this->FormToken = new PHPFormToken();


    }


    public function Form()
    {
        $param['ID'] = '';
        $param['name'] = '';
        $param['save'] = 'Save';

        $this->FormToken->SetFormToken("ReportDataToken");

        $param["formToken"] = $this->FormToken->GetFormToken("ReportDataToken");

        if (isset($_GET['ID']) && $_GET['ID'] > 0) {
            $data = $this->OperatorAndServiceRepo->GetById($_GET['ID']);

            $param['Country'] = $data->Country;

            $param['Operator'] = $data->Operator;

            $param['Service'] = $data->Service;

            $param['ID'] = $data->ID;

            $param['save'] = "Update";
        }

       
        return $param;
    }


}