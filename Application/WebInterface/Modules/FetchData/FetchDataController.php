<?php

namespace WebInterface\Modules\FetchData;


use Repositories\ServicesRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;
use WebInterface\Models\OperatorAndServicesRelation;

//class FetchDataController extends WebInterfaceControllerAbstract
class FetchDataController
{


    function __construct()
    {
        $this->fetchDataRepository = new FetchDataRepository();


      // parent::__construct();
    }

    function IndexAction(){

    }

    function SetCronAction(){

      //  shell_exec("crontab -r");

      //  $output = shell_exec('echo "20 06 * * * wget demo.unifun.com/UnifunPromo/FetchData/FetchDataInflow" | crontab -');

       // $cmdExec = "(crontab -l 2>/dev/null; echo '".'30 06 * * * wget demo.unifun.com/UnifunPromo/FetchData/FetchDataBalancePlusView'."') | crontab - 2>&1; echo $? ";
        //shell_exec($cmdExec);
    }


    function FetchDataInflowAction()
    {


        $this->fetchDataRepository->GetInflowFromMSSQL();

    }

    function FetchDataBalancePlusViewAction()
    {

		$this->FetchDataInflowAction();
	

        $this->fetchDataRepository->GetBalancePlusViewFromMSSQL();
		
		
		

    }


    function  FetchStoredProcAction(){

        $this->fetchDataRepository->FetchStoredProc();

    }

    function SetStoredAction(){

        $this->fetchDataRepository->SetProc();
    }




}