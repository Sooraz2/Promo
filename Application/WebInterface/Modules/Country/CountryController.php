<?php

namespace WebInterface\Modules\Country;

use Repositories\CountryRepository;
use Repositories\OperatorRepository;
use Repositories\ServicesRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\BroadcastingCalendar;
use WebInterface\Models\Country;
use WebInterface\Modules\BroadcastingCalendar\BroadcastingCalendarConfirmation;
use WebInterface\Modules\Country\CountryParameter;

class CountryController extends WebInterfaceControllerAbstract
{
    private $teaserTableRepository;

    function __construct()
    {

        $this->CountryRepo = new CountryRepository();

        $this->FormToken = new PHPFormToken();

        $this->CountryParameter = new CountryParameter();

        parent::__construct();
    }

    function SaveUpdateAction()
    {

        $Country = new Country();

        $Country->MapParameters($_POST);

        if ($_POST['id'] == '') {

            $status = $this->CountryRepo->Save($Country);

            CountryConfirmation::Save($status);

            Redirect("OperatorAndServices");
        } else {

            $status = $this->CountryRepo->Update($Country);

            CountryConfirmation::Update($status);

            Redirect("OperatorAndServices");
        }

    }

    function ListAllAction()
    {

        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        echo json_encode($this->CountryRepo->FindAll($ajaxGrid));

    }

    function FormAction()
    {

        $this->load->View("Country/Form", $this->CountryParameter->Form());

    }

    function DeleteAction()
    {


        $status = $this->CountryRepo->Delete($_POST['ID']);

        CountryConfirmation::Delete($status);

        Redirect("OperatorAndServices");
    }

    function CheckCountryAction(){

        $response[0] = $_GET["fieldId"];

        $id = $_GET['id'];

        $response[1] = $this->CountryRepo->CheckCountryExists($_GET['fieldValue'], $id) > 0 ? false : true;

        echo json_encode($response);
    }

}