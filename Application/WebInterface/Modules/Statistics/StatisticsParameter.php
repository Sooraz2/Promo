<?php

namespace WebInterface\Modules\Statistics;


use Repositories\CountryRepository;

class StatisticsRelationParameter
{


    function __construct()
    {
        $this->OperatorAndServiceRepo = new OperatorAndServicesRelationRepository();


    }


    public function Form()
    {
        $param['ID'] = '';
        $param['name'] = '';
        $param['save'] = 'Save';
        $param['AllCountry'] = $this->OperatorAndServiceRepo->GetAllCountry();
        $param['AllOperator'] = $this->OperatorAndServiceRepo->GetAllOperator();
        $param['AllService'] = $this->OperatorAndServiceRepo->GetAllService();
        $param['Country'] = '';
        $param['Operator'] = '';
        $param['Service'] = '';

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