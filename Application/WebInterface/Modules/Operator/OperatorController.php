<?php

namespace WebInterface\Modules\Operator;


use Repositories\OperatorRepository;
use Repositories\ServicesRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use Infrastructure\PHPFormToken;
use WebInterface\Models\Operator;


class OperatorController extends WebInterfaceControllerAbstract
{


    function __construct()
    {

        $this->OperatorRepo = new OperatorRepository();

        $this->FormToken = new PHPFormToken();

        $this->OperatorParameter = new OperatorParameter();

        parent::__construct();
    }




    function SaveUpdateAction()
    {

        $Operator = new Operator();

        $Operator->MapParameters($_POST);

        if ($_POST['id'] == '') {

            $status = $this->OperatorRepo->Save($Operator);

            OperatorConfirmation::Save($status);

            Redirect("OperatorAndServices");
        } else {

            $status = $this->OperatorRepo->Update($Operator);

            OperatorConfirmation::Update($status);

            Redirect("OperatorAndServices");
        }

    }

    function ListAllAction()
    {

        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        echo json_encode($this->OperatorRepo->FindAll($ajaxGrid));

    }

    function FormAction()
    {

        $this->load->View("Operator/Form", $this->OperatorParameter->Form());

    }

    function DeleteAction()
    {

        $status = $this->OperatorRepo->Delete($_POST['ID']);

        OperatorConfirmation::Delete($status);

        Redirect("OperatorAndServices");
    }

    function CheckOperatorAction(){

        $response[0] = $_GET["fieldId"];

        $id = $_GET['id'];

        $response[1] = $this->OperatorRepo->CheckOperatorExists($_GET['fieldValue'], $id) > 0 ? false : true;

        echo json_encode($response);
    }

}