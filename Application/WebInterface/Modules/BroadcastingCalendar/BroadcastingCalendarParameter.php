<?php

namespace WebInterface\Modules\BroadcastingCalendar;


use Infrastructure\PHPFormToken;
use Repositories\broadcastingRepository;
use WebInterface\Models\BroadcastingCalendar;

class BroadcastingCalendarParameter
{

    private $teaserRepository;

    function __construct()
    {
        $this->broadcastingRepository = new BroadcastingCalendarRepository();

        $this->FormToken = new PHPFormToken();

    }

    public function Index()
    {
        $param['PageIndex'] = 1;

        if (isset($_GET['PageIndex']) && $_GET['PageIndex'] != "") {
            $param['PageIndex'] = $_GET['PageIndex'];
        }

        $param["CountryList"] = $this->broadcastingRepository->GetAllCountries();
        $param["OperatorList"] = $this->broadcastingRepository->GetAllOperators();
        $param["OperatorServiceList"] = $this->broadcastingRepository->GetAllOperatorsServices();

        return $param;
    }



    public function FormNew()
    {

        $param['Date'] = $_GET['Date'];
        $param['Country'] =  $_GET['Country'];
        $param['Operator'] =    $_GET['Operator'];
        $param['Service'] =   $_GET['Service'];
        $param['Promotion'] =   $_GET['Promotion'];
        return $param;
    }


    public function  BoardCastingForm()
    {

        $param['Save'] = 'Save';
        $param['BroadcastDate'] = '';
        $BoardCastingCalendar = new BroadcastingCalendar();

        if(isset($_GET['ID']) && $_GET['ID']> 0 ) {
            $data = $this->broadcastingRepository->GetByIdBroadcasting($_GET['ID'],'id');
            $param['BroadcastDate'] = $data['broadcastDate'];
            $BoardCastingCalendar->MapParameters($data);
            $param['Save'] = 'Update';

        } else {
            $BoardCastingCalendar->MapParameters($_GET);
        }

        $param['All'] = $BoardCastingCalendar;

        $param["OperatorServiceList"] = $this->broadcastingRepository->GetServiceByOperatorAndCountry($BoardCastingCalendar->country,$BoardCastingCalendar->operator);

        return $param;
    }


}