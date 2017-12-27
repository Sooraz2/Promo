<?php

namespace WebInterface\Modules\Statistics;


use Repositories\ServicesRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use WebInterface\Models\OperatorAndServicesRelation;

class StatisticsController extends WebInterfaceControllerAbstract
{


    function __construct()
    {
        $this->statisticsRepository = new StatisticsRepository();

        parent::__construct();
    }

    function IndexAction()
    {

        $param['Operator'] = $this->statisticsRepository->GetAllOperator();
        $param['Service'] = $this->statisticsRepository->GetAllService();
        $param['CountryList'] = $this->statisticsRepository->GetAllCountry();
        $param['CountryAndOperatorList'] = $this->statisticsRepository->GetCountryAndService();
        $param['CountryAndOperatorAndService'] = $this->statisticsRepository->GetServiceByCountryOperatorAll();
        $param['CountryListAll'] = json_encode($this->statisticsRepository->GetArrayFromObjects($param['CountryList']));
        $param['OperatorListAll'] = json_encode($this->statisticsRepository->GetArrayFromObjects($param['Operator']));
        $param['ServiceListAll'] = json_encode($this->statisticsRepository->GetArrayFromObjects( $param['Service']));


        $this->load->View("Statistics/Index", $param);

    }

    function SaveUpdateAction()
    {

        $OperatorAndService = new OperatorAndServicesRelation();

        $OperatorAndService->MapParameters($_POST);

        if ($OperatorAndService->ID == '') {

            $status = $this->OperatorAndServiceRepo->Save($OperatorAndService);

            OperatorAndServicesRelationConfirmation::Save($status);

        } else {

            $status = $this->OperatorAndServiceRepo->Update($OperatorAndService);

            OperatorAndServicesRelationConfirmation::Update($status);
        }

        Redirect("OperatorAndServicesRelation");

    }

    function ListAllAction()
    {

        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        echo json_encode($this->OperatorAndServiceRepo->FindAll($ajaxGrid));

    }

    function FormAction()
    {

        $this->load->View("OperatorAndServicesRelation/Form", $this->OperatorAndServiceParameter->Form());

    }

    function DeleteAction()
    {

        $status = $this->OperatorAndServiceRepo->Delete($_POST['ID']);

        OperatorAndServicesRelationConfirmation::Delete($status);

        Redirect("OperatorAndServicesRelation");
    }

    function CheckCountryAction()
    {

        $response[0] = $_GET["fieldId"];

        $id = $_GET['id'];

        $response[1] = $this->CountryRepo->CheckCountryExists($_GET['fieldValue'], $id) > 0 ? false : true;

        echo json_encode($response);
    }


    function StatChartInflowOutflowAction()
    {

      $Date =   isset($_GET['filterData']['Date']) && $_GET['filterData']['Date'] !='' ? $_GET['filterData']['Date'] : $this->statisticsRepository->GetCurrentDate();
      $Country =   isset($_GET['filterData']['Country']) && $_GET['filterData']['Country'] !='' ? $_GET['filterData']['Country'] :'';
      $Operator =   isset($_GET['filterData']['Operator']) && $_GET['filterData']['Operator'] !='' ? $_GET['filterData']['Operator'] : '';
      $Service =   isset($_GET['filterData']['Service']) && $_GET['filterData']['Service']!='' ? $_GET['filterData']['Service'] : '';

        echo json_encode($this->statisticsRepository->GetStatData($Date,$Country,$Operator,$Service));
    }

    function StatChartViewsAction()
    {

        $Date =   isset($_GET['filterData']['Date']) && $_GET['filterData']['Date'] !='' ? $_GET['filterData']['Date'] : $this->statisticsRepository->GetCurrentDate();
        $Country =   isset($_GET['filterData']['Country']) && $_GET['filterData']['Country'] !='' ? $_GET['filterData']['Country'] :'';
        $Operator =   isset($_GET['filterData']['Operator']) && $_GET['filterData']['Operator'] !='' ? $_GET['filterData']['Operator'] : '';
        $Service =   isset($_GET['filterData']['Service']) && $_GET['filterData']['Service']!='' ? $_GET['filterData']['Service'] : '';

        echo json_encode($this->statisticsRepository->GetStatData($Date,$Country,$Operator,$Service));
    }

    function FetchDataAction()
    {
        $this->statisticsRepository->GetInflowFromMSSQL();

    }

   function ListAllOperator1Action(){

       $Date =   isset($_GET['filterData']['Date']) && $_GET['filterData']['Date'] !='' ? $_GET['filterData']['Date'] : $this->statisticsRepository->GetCurrentDate();
       $Country =   isset($_GET['filterData']['Country']) && $_GET['filterData']['Country'] !='' ? $_GET['filterData']['Country'] :'';
       $Operator =   isset($_GET['filterData']['Operator']) && $_GET['filterData']['Operator'] !='' ? $_GET['filterData']['Operator'] : '';
       $Service =   isset($_GET['filterData']['Service']) && $_GET['filterData']['Service']!='' ? $_GET['filterData']['Service'] : '';
       echo  json_encode($this->statisticsRepository->ListAverageOperator1($Date,$Country,$Operator,$Service));
   }


    function ListAllOperator2Action(){

        $Date =   isset($_GET['filterData']['Date']) && $_GET['filterData']['Date'] !='' ? $_GET['filterData']['Date'] : $this->statisticsRepository->GetCurrentDate();
        $Country =   isset($_GET['filterData']['Country']) && $_GET['filterData']['Country'] !='' ? $_GET['filterData']['Country'] :'';
        $Operator =   isset($_GET['filterData']['Operator']) && $_GET['filterData']['Operator'] !='' ? $_GET['filterData']['Operator'] : '';
        $Service =   isset($_GET['filterData']['Service']) && $_GET['filterData']['Service']!='' ? $_GET['filterData']['Service'] : '';
        echo  json_encode($this->statisticsRepository->ListAverageOperator2($Date,$Country,$Operator,$Service));
    }

    function GetPromotionDetailsAction(){

        $Date =   isset($_GET['filterData']['Date']) && $_GET['filterData']['Date'] !='' ? $_GET['filterData']['Date'] : $this->statisticsRepository->GetCurrentDate();
        $Country =   isset($_GET['filterData']['Country']) && $_GET['filterData']['Country'] !='' ? $_GET['filterData']['Country'] :'';
        $Operator =   isset($_GET['filterData']['Operator']) && $_GET['filterData']['Operator'] !='' ? $_GET['filterData']['Operator'] : '';
        $Service =   isset($_GET['filterData']['Service']) && $_GET['filterData']['Service']!='' ? $_GET['filterData']['Service'] : '';

        echo json_encode($this->statisticsRepository->ShowPromotionTable($Date,$Country,$Operator,$Service));
    }

    function GetServiceByCountryAndOperatorAction(){

        $Country =   isset($_GET['Country']) && $_GET['Country'] !='' ? $_GET['Country'] :'';
        $Operator =   isset($_GET['Operator']) && $_GET['Operator'] !='' ? $_GET['Operator'] : '';

        echo json_encode($this->statisticsRepository->GetServiceByCountryOperator($Country,$Operator));
    }

}