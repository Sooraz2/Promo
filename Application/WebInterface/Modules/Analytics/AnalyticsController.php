<?php
namespace WebInterface\Modules\Analytics;

use Repositories\OperatorRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\BroadcastingCalendar;
use WebInterface\Modules\BroadcastingCalendar\BroadcastingCalendarConfirmation;
use WebInterface\Modules\Statistics\StatisticsRepository;

class AnalyticsController extends WebInterfaceControllerAbstract
{

    function __construct()
    {
        $this->analyticsRepository = new AnalyticsRepository();
        $this->statisticsRepository = new StatisticsRepository();

        parent::__construct();
    }

    function IndexAction()
    {

        $param['CountryList'] = $this->statisticsRepository->GetAllCountry();
        $param['CountryListAll'] = json_encode($this->statisticsRepository->GetArrayFromObjects($param['CountryList']));
        $this->load->View("Analytics/Index", $param);
    }


    function GetOperatorByCountryAction(){

        $Country = $_POST['CountryID'];

        echo json_encode($this->statisticsRepository->GetArrayFromObjects($this->analyticsRepository->GetOperatorByCountry($Country)));
    }

    function  GetAllAction(){

        $DateFrom =   isset($_GET['filterData']['DateFrom']) && $_GET['filterData']['DateFrom'] !='' ? $_GET['filterData']['DateFrom'] : '2050-20-20';
        $DateTo =   isset($_GET['filterData']['DateTo']) && $_GET['filterData']['DateTo'] !='' ? $_GET['filterData']['DateTo'] : '2050-20-20';
        $Country =   isset($_GET['filterData']['Country']) && $_GET['filterData']['Country'] !='' ? $_GET['filterData']['Country'] :'';
        $Operator =   isset($_GET['filterData']['Operator']) && $_GET['filterData']['Operator'] !='' ? $_GET['filterData']['Operator'] : '';
        $Service =   isset($_GET['filterData']['Service']) && $_GET['filterData']['Service']!='' ? $_GET['filterData']['Service'] : '';
        $Promotion =   isset($_GET['filterData']['Service']) && $_GET['filterData']['Promotion']!='' ? $_GET['filterData']['Promotion'] : '';

        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        echo json_encode($this->analyticsRepository->FindAll($ajaxGrid,$DateFrom,$DateTo,$Country,$Operator,$Service,$Promotion));
    }

    function  ExportAction(){


        $DateFrom =   isset($_POST['DateFrom']) && $_POST['DateFrom'] !='' ? $_POST['DateFrom'] :'';
        $DateTo =   isset($_POST['DateTo']) && $_POST['DateTo'] !='' ? $_POST['DateTo'] :'';
        $Country =   isset($_POST['CountryExport']) && $_POST['CountryExport'] !='' ? $_POST['CountryExport'] :'None';
        $Operator =   isset($_POST['OperatorExport']) && $_POST['OperatorExport'] !='' ? $_POST['OperatorExport'] : 'None';
        $Service =   isset($_POST['ServiceExport']) && $_POST['ServiceExport'] !='' ? $_POST['ServiceExport'] : 'None';
        $Promotion =   isset($_POST['PromotionList']) && $_POST['PromotionList'] !='' ? $_POST['PromotionList']: 'None';

        $filename = "exports/UnifunPromoAnalystics_".generateRandomAlphaNumericString(4).".xlsx";

        $path = "python ExportAllReport.py $DateFrom $DateTo \"$Country\" \"$Operator\" \"$Service\" \"$Promotion\" $filename";

        //$command = escapeshellcmd($path);

        $output = shell_exec($path);

        if (trim($output) == "ExportSuccess") {

                ob_start();
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-Disposition: attachment;filename=\"$filename\"");
                header("Pragma: public");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header('Content-Length: ' . filesize($filename));
                ob_clean();
                flush();
               readfile($filename);
                  // unlink($filename);

        }

    }


    function GetServiceByOperatorAction(){

        $Operator = $_POST['OperatorID'];
        $CountryID = $_POST['CountryID'];

        echo json_encode($this->analyticsRepository->GetServiceByOperator($Operator,$CountryID));


    }

}