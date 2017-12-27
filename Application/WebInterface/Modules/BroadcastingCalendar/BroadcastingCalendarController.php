<?php

namespace WebInterface\Modules\BroadcastingCalendar;

use Repositories\OperatorRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\BroadcastingCalendar;
use WebInterface\Modules\BroadcastingCalendar\BroadcastingCalendarConfirmation;

class BroadcastingCalendarController extends WebInterfaceControllerAbstract
{
    private $broadcastingRepository;

    function __construct()
    {
        $this->broadcastingRepository = new BroadcastingCalendarRepository();

        $this->broadcastingCalender = new BroadcastingCalendarParameter();

        $this->FormToken = new PHPFormToken();

        $this->OperatorRepo = new OperatorRepository();

        parent::__construct();
    }

    function IndexAction()
    {
        $this->load->View("BroadcastingCalendar/Index", $this->broadcastingCalender->Index());
    }


    function GetBroadCastingDataNewAction()
    {

        $dateFrom = $_GET['from'];
        $dateTo = $_GET['to'];
        $country = $_GET['CountryID'];
        $operator = $_GET['OperatorID'];
        $service = $_GET['ServiceID'];
        $promotion = $_GET['PromotionID'];
        $promotionText = $_GET['PromotionText'];


        echo json_encode($this->broadcastingRepository->GetAllBroadcastingData($country, $operator, $service, $promotion, $promotionText, $dateFrom, $dateTo));
    }


    function GetBroadCastingDetailsDataNewAction()
    {
        $this->load->View("BroadcastingCalendar/List", $this->broadcastingCalender->FormNew());
    }

    function  GetAllAction()
    {


        $ajaxGrid = new AjaxGrid();

        $Country = isset($_GET['Country']) && $_GET['Country'] != '' ? $_GET['Country'] : '';
        $Operator = isset($_GET['Operator']) && $_GET['Operator'] != '' ? $_GET['Operator'] : '';
        $Service = isset($_GET['Service']) && $_GET['Service'] != '' ? $_GET['Service'] : '';
        $Promotion = isset($_GET['Promotion']) && $_GET['Promotion'] != '' ? $_GET['Promotion'] : '';

        $ajaxGrid->MapParameters($_GET);
        $Date = $_GET['Date'];
        echo json_encode($this->broadcastingRepository->GetAllBrocastingPromotion($ajaxGrid, $Date, $Country, $Operator, $Service, $Promotion));
    }

    function FormAction()
    {

        $this->load->View("BroadcastingCalendar/Form", $this->broadcastingCalender->BoardCastingForm());
    }

    function SaveAction()
    {

        if ($_POST['promotion'] == 'Wrong Star(*)' || $_POST['promotion'] == 'Wrong IVR') {
            $_POST['text'] = array('');
        }

        $BroadcastingCalendar = new BroadcastingCalendar();

        if ($_POST['id'] == '') {

            $BroadcastingCalendar->MapParameters($_POST);

            $BroadcastingCalendar->dateadded = $this->broadcastingRepository->GetCurrentDateTime();

            foreach ($_POST['first_date_time'] as $date) {

                $newdateformat = date('Y-m-d', strtotime($date));

                $BroadcastingCalendar->dateto = $BroadcastingCalendar->datefrom = $newdateformat;

                foreach ($_POST['text'] as $text) {

                    $BroadcastingCalendar->text = $text;

                    $status = $this->broadcastingRepository->Save($BroadcastingCalendar);

                }
                BroadcastingCalendarConfirmation::Save($status);
            }

        } else {


            $BroadcastingCalendar->MapParameters($_POST);

            $deleteStatus = $this->broadcastingRepository->Delete($_POST['id'], 'id');

            $BroadcastingCalendar->dateadded = $this->broadcastingRepository->GetCurrentDateTime();

            foreach ($_POST['first_date_time'] as $date) {

                $newdateformat = date('Y-m-d', strtotime($date));

                $BroadcastingCalendar->dateto = $BroadcastingCalendar->datefrom = $newdateformat;

                foreach ($_POST['text'] as $text) {

                    $BroadcastingCalendar->text = $text;

                    $status = $this->broadcastingRepository->Save($BroadcastingCalendar);

                }

                BroadcastingCalendarConfirmation::Update($status);
            }

        }

        return $status;
        //   Redirect("BroadcastingCalendar");

    }

    function DeleteAction()
    {


        $status = $this->broadcastingRepository->Delete($_POST['ID'], 'id');

        BroadcastingCalendarConfirmation::Delete($status);

        Redirect("BroadcastingCalendar");
    }

    function GetOperatorByCountryAction()
    {

        $Country = $_POST['CountryID'];

        echo json_encode($this->OperatorRepo->GetOperatorByCountry($Country));

    }

    function GetServiceByOperatorAction()
    {

        $Operator = $_POST['OperatorID'];

        $CountryID = $_POST['CountryID'];

        echo json_encode($this->OperatorRepo->GetServiceByOperator($Operator, $CountryID));

    }

    function CheckServiceAction()
    {

        $response[0] = $_GET["fieldId"];

        $response[1] = true;


        if ($_GET['Promotion'] == 'Wrong IVR' || $_GET['Promotion'] == 'Wrong Star(*)') {


            $dateCheck = array();

            $dates = explode(',', $_GET['Dates']);

            foreach ($dates as $date) {

                $time = strtotime($date);

                $dateFormat = date('Y-m-d', $time);

                $status = $this->broadcastingRepository->CheckPromotionDetails($dateFormat, $_GET['Country'], $_GET['Operator'], $_GET['fieldValue'], $_GET['ID']);

                array_push($dateCheck, $status);
            }

            $response[0] = $_GET["fieldId"];

            $response[1] = in_array(1, $dateCheck) ? false : true;

        }

        echo json_encode($response);


    }

}