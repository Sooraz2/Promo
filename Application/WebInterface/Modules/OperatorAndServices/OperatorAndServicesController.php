<?php

namespace WebInterface\Modules\OperatorAndServices;

use Repositories\CountryRepository;
use Repositories\OperatorRepository;
use Repositories\ServiceRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\BroadcastingCalendar;
use WebInterface\Models\Country;
use WebInterface\Modules\BroadcastingCalendar\BroadcastingCalendarConfirmation;

class OperatorAndServicesController extends WebInterfaceControllerAbstract
{
    private $teaserTableRepository;

    function __construct()
    {
        $this->OperatorRepo = new OperatorRepository();
        $this->ServicesRepo = new ServiceRepository();
        $this->CountryRepo = new CountryRepository();

        $this->FormToken = new PHPFormToken();

        parent::__construct();
    }

    function IndexAction()
    {
        $this->load->View("OperatorAndServices/Index");
    }


    function GetBroadCastingDetailsDataNewAction()
    {

        $this->load->View("BroadcastingCalendar/List",$this->broadcastingCalender->FormNew());
    }

    function  GetAllAction(){


        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);
        $date = $_GET['Date'];
        echo json_encode($this->teaserTableRepository->GetAllBrocastingPromotion($ajaxGrid,$date));
    }


    function SaveAction()
    {

        $BroadcastingCalendar = new BroadcastingCalendar();

        if ($_POST['id'] == '') {

            $BroadcastingCalendar->MapParameters($_POST);

            $BroadcastingCalendar->dateadded = $this->teaserTableRepository->GetCurrentDateTime();

            foreach ($_POST['first_date_time'] as $date) {

                $newdateformat = date('Y-m-d', strtotime($date));

                $BroadcastingCalendar->dateto = $BroadcastingCalendar->datefrom = $newdateformat;

                $status = $this->teaserTableRepository->Save($BroadcastingCalendar);

                BroadcastingCalendarConfirmation::Save($status);
            }
            Redirect("BroadcastingCalendar");
        }

    }

    function SaveUpdateCountryAction(){

        $Country = new Country();
        $Country->MapParameters($_GET);


    }

    function ListCountryAction(){

            $ajaxGrid = new AjaxGrid();

            $ajaxGrid->MapParameters($_GET);

            echo json_encode($this->loginUserRepository->FindAll($ajaxGrid, $this->language->LanguageClass));

    }


    function DeleteAction(){

      $status =   $this->teaserTableRepository->Delete($_POST['ID']);

        BroadcastingCalendarConfirmation::Delete($status);

        Redirect("BroadcastingCalendar");
    }

}