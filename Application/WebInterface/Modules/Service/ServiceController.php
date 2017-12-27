<?php

namespace WebInterface\Modules\Service;


use Repositories\ServiceRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\Service;



class ServiceController extends WebInterfaceControllerAbstract
{


    function __construct()
    {

        $this->ServiceRepo = new ServiceRepository();

        $this->FormToken = new PHPFormToken();

        $this->ServiceParameter = new ServiceParameter();

        parent::__construct();
    }




    function SaveUpdateAction()
    {

        $Service = new Service();

        $Service->MapParameters($_POST);

        if ($_POST['id'] == '') {

            $status = $this->ServiceRepo->Save($Service);

            ServiceConfirmation::Save($status);

            Redirect("OperatorAndServices");
        } else {

            $status = $this->ServiceRepo->Update($Service);

            ServiceConfirmation::Update($status);

            Redirect("OperatorAndServices");
        }

    }

    function ListAllAction()
    {

        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        echo json_encode($this->ServiceRepo->FindAll($ajaxGrid));

    }

    function FormAction()
    {

        $this->load->View("Service/Form", $this->ServiceParameter->Form());

    }

    function DeleteAction()
    {

        $status = $this->ServiceRepo->Delete($_POST['ID']);

        ServiceConfirmation::Delete($status);

        Redirect("OperatorAndServices");
    }

    function CheckServiceAction(){

        $response[0] = $_GET["fieldId"];

        $id = $_GET['id'];

        $response[1] = $this->ServiceRepo->CheckServiceExists($_GET['fieldValue'], $id) > 0 ? false : true;

        echo json_encode($response);
    }

}