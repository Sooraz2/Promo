<?php
namespace WebInterface\Modules\ReportData;

use Infrastructure\SessionVariables;
use Repositories\OperatorRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\BroadcastingCalendar;
use WebInterface\Models\ReportData;
use WebInterface\Modules\BroadcastingCalendar\BroadcastingCalendarConfirmation;
use WebInterface\Modules\FetchData\FetchDataRepository;
use WebInterface\Modules\Statistics\StatisticsRepository;

class ReportDataController extends WebInterfaceControllerAbstract
{

    function __construct()
    {
        $this->fetchDataRepo = new FetchDataRepository();
        $this->reportDataRepository = new ReportDataRepository();
        $this->reportDataParameter = new ReportDataParameter();

            $this->FormToken = new PHPFormToken();

        parent::__construct();

    }

    function IndexAction()
    {

        $this->load->View("ReportData/Index");
    }


    function FormAction()
    {

        $this->load->View("ReportData/Form", $this->reportDataParameter->Form());

    }
    function  GetAllAction(){

        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        echo json_encode($this->reportDataRepository->FindAll($ajaxGrid));
    }

    function CheckProcudureAction(){

       echo json_encode($this->fetchDataRepo->CheckProcudure($_POST['procudureName']));
    }

    function SaveAction(){

        if ($this->FormToken->GetFormToken("ReportDataToken") == $_POST['ReportDataToken']) {

            $this->FormToken->UnsetFormToken("ReportDataToken");

            $currentDate = $this->reportDataRepository->GetCurrentDate();

            $monthBack = date('Y-m-d', strtotime('-30 day', strtotime($currentDate)));

            $ReportData = new ReportData();

            $ReportData->MapParameters($_POST);

            $storedProcudure = "[VCHReports].[dbo].[" . $_POST['name'] . "] ";

            $callableProcudure = $storedProcudure;

            $ReportData->name = $storedProcudure;

            $ReportData->id = '';

            $allParam = '';

            $allParam1 = '';

            $dateParam = '@DateFrom';

            foreach ($_POST['params'] as $param) {
                if (strpos($param, '@') !== false) {

                    $allParam .= ", " . $dateParam;

                    $dateParam = '@Datetime';

                    if (strpos(strtolower($param), 'date') !== false) {

                        $allParam1 .= ", '" . $monthBack . "'";

                        $monthBack = $currentDate;
                    }

                } else {

                    $allParam .= ", '" . $param . "'";

                    $allParam1 .= ", '" . $param . "'";
                }

            }

            $storedProcudure .= ltrim($allParam, ',');
            $callableProcudure .= ltrim($allParam1, ',');

            $ReportData->param = $storedProcudure;

            $status = $this->fetchDataRepo->FetchStoredProc($ReportData, $callableProcudure);

            if ($status == true) {

                $_SESSION[SessionVariables::$ConfirmationMessage] = 'Procudure Successfully Added';

                $_SESSION[SessionVariables::$ConfirmationMessageType] = "Success";

            }

            Redirect('ReportData');
        }

    }

    function CheckProcudureExistAction(){

        $response[0] = $_GET["fieldId"];

        $id = 0;

        $response[1] = $this->reportDataRepository->CheckProcudureExists($_GET['fieldValue'], $id) > 0 ? false : true;

        echo json_encode($response);
    }

    function DeleteAction(){

        $ReportData = $this->reportDataRepository->GetById($_POST['ID']);

        if($ReportData->id > 0) {

            $status = $this->fetchDataRepo->DeleteFromProc($ReportData);
            if ($status) {

                $_SESSION[SessionVariables::$ConfirmationMessage] = 'Procudure Successfully Deleted';

                $_SESSION[SessionVariables::$ConfirmationMessageType] = "Success";
            }
        }

        Redirect('ReportData');

    }

}