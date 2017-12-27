<?php

namespace WebInterface\Modules\OperatorAndServices;


use Infrastructure\PHPFormToken;
use Repositories\TeaserTableRepository;

class OperatorAndServicesParameter
{

    private $teaserRepository;

    function __construct()
    {
        $this->teaserTableRepository = new BroadcastingCalendarRepository();

        $this->FormToken = new PHPFormToken();

        $this->teaserRepository = new TeaserTableRepository();
    }

    public function Index()
    {
        $param['PageIndex'] = 1;

        if (isset($_GET['PageIndex']) && $_GET['PageIndex'] != "") {
            $param['PageIndex'] = $_GET['PageIndex'];
        }

        $param["ServiceOptions"] = $this->teaserRepository->GetAllServices();
        $param["CountryList"] = $this->teaserTableRepository->GetAllCountries();
        $param["OperatorList"] = $this->teaserTableRepository->GetAllOperators();
        $param["OperatorServiceList"] = $this->teaserTableRepository->GetAllOperatorsServices();

        return $param;
    }

    public function Form()
    {
        $date = $_GET['date'];

        $teaserId = $_GET["teaserID"];

        $ServiceName = $_GET["ServiceName"];

        $priorityList = json_encode($this->teaserTableRepository->GetPriority());

        $currentDate = $this->teaserTableRepository->GetCurrentDate();

        $param = array("Date" => $date, "PriorityList" => $priorityList, "CurrentDate" => $currentDate, "teaserID" => $teaserId, "ServiceName" => $ServiceName);

        if (isset($_SESSION['TeaserCurrentPage'])) {
            $param['PageIndex'] = isset($_SESSION['TeaserCurrentPage']) && $_SESSION['TeaserCurrentPage'] != '' ? $_SESSION['TeaserCurrentPage'] : 1;
            unset($_SESSION['TeaserCurrentPage']);
        }

        return $param;
    }

    public function FormNew()
    {
        $param['Date'] = $_GET['date'];

        return $param;
    }


    public function  BoardCastingForm()
    {

        $param["OperatorServiceList"] = $this->teaserTableRepository->GetAllOperatorsServices();
        $param["Country"] = $_GET['country'];
        $param["Operator"] = $_GET['operator'];

        return $param;
    }


}