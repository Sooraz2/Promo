<?php

namespace WebInterface\Modules\OperatorAndServicesRelation;

use Repositories\CountryRepository;
use Repositories\OperatorRepository;
use Repositories\ServicesRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\BroadcastingCalendar;
use WebInterface\Models\Country;
use WebInterface\Models\OperatorAndServicesRelation;
use WebInterface\Modules\BroadcastingCalendar\BroadcastingCalendarConfirmation;
use WebInterface\Modules\Country\CountryParameter;

class OperatorAndServicesRelationController extends WebInterfaceControllerAbstract
{


    function __construct()
    {

        $this->CountryRepo = new CountryRepository();

        $this->FormToken = new PHPFormToken();

        $this->CountryParameter = new CountryParameter();

        $this->OperatorAndServiceParameter = new OperatorAndServicesRelationParameter();

        $this->OperatorAndServiceRepo = new OperatorAndServicesRelationRepository();

        parent::__construct();
    }
    function IndexAction(){

        $this->load->View("OperatorAndServicesRelation/Index");

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

    function CheckCountryAction(){

        $response[0] = $_GET["fieldId"];

        $id = $_GET['id'];

        $response[1] = $this->CountryRepo->CheckCountryExists($_GET['fieldValue'], $id) > 0 ? false : true;

        echo json_encode($response);
    }

}