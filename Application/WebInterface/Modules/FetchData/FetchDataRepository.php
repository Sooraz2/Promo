<?php

namespace WebInterface\Modules\FetchData;


use System\Repositories\Repo;
use WebInterface\Models\ReportData;


class FetchDataRepository extends Repo
{


    function __construct()
    {
        parent::__construct('','');
    }


    function GetInflowFromMSSQLpp()
    {

        $currentDate = $this->GetCurrentDate();

         $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));

		//$yesterday = '2017-06-20';

        $result = mssql_query($this->dbConnectionMssql, "SELECT * FROM Unifun_Promo WHERE `Date` > '$yesterday' ");

        while ($obj = mssql_fetch_object($result)) {

            $dateObj = (array)$obj->Date;

            $date = $dateObj['date'];

            //var_dump($dateObj);

            $sql = "INSERT INTO operatorinflowoutflow (Date,Country,Operator,Service,Inflow,Outflow) VALUES('$date','$obj->Country','$obj->Operator','$obj->Service',$obj->Inflow,$obj->Outflow)";

            $this->GetDbConnection()->query($sql);

        }
    }

    function GetBalancePlusViewFromMSSQLpp()
    {

        $currentDate = $this->GetCurrentDate();

        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));

      //  $result = mssql_query($this->dbConnectBPlusMsSql, "SELECT  * FROM GeneralStatistics WHERE Date = '$yesterday'");
        $result = mssql_query($this->dbConnectBPlusMsSql, "SELECT * FROM GeneralStatistics");

        while ($obj = mssql_fetch_object($result)) {

            $dateObj = (array)$obj->Date;

            $date = $dateObj['date'];

            $teaserText = mysql_real_escape_string($obj->TeaserText);

            $sql = "INSERT INTO BalanceplusView (Date,Name,PromotionText,Views,Country,Operator,Service,UniqueViews) VALUES('$date','$obj->Operator','$teaserText',$obj->Views,'$obj->Country','$obj->Operator1','$obj->Service',$obj->UniqueViews)";

            $this->GetDbConnection()->query($sql);

        }

    }


    function GetInflowFromMSSQL()
    {


        $currentDate = $this->GetCurrentDate();

        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));

        //$yesterday = '2017-11-23';

        $this->GetDbConnection()->query("DELETE FROM operatorinflowoutflow WHERE Date > '$yesterday' ");

        //$this->GetDbConnection()->query("INSERT INTO crontext VALUES('$yesterday')");

        $result = mssql_query("SELECT * FROM Unifun_Promo WHERE Date  = '$yesterday' ",$this->dbConnectionMssql);

        while ($obj = mssql_fetch_object($result)) {

            $dateObj = (array)$obj->Date;

            $date = $dateObj[0];

            $sql = "INSERT INTO operatorinflowoutflow (Date,Country,Operator,Service,Inflow,Outflow) VALUES('$date','$obj->Country','$obj->Operator','$obj->Service',$obj->Inflow,$obj->Outflow)";

			
            $this->GetDbConnection()->query($sql);

        }
    }

    function GetBalancePlusViewFromMSSQL()
    {


        $currentDate = $this->GetCurrentDate();

        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($currentDate)));

        //  $result = mssql_query($this->dbConnectBPlusMsSql, "SELECT  * FROM GeneralStatistics WHERE Date = '$yesterday'");
        $this->GetDbConnection()->query("DELETE FROM BalanceplusView WHERE Date = '$yesterday' ");

        //$yesterday = '2017-11-23';

        $result = mssql_query( "SELECT * FROM GeneralStatistics WHERE Date = '$yesterday' ",$this->dbConnectBPlusMsSql);

         while ($obj = mssql_fetch_object($result)) {

         
           $dateObj = (array)$obj->Date;

           $date = $dateObj[0];

           $date =  date("Y-m-d", strtotime($date) );

           $teaserText =$obj->TeaserText;
		   $service = $obj->Service;

		  // echo $teaserText.PHP_EOL.PHP_EOL.'<br><br>';
           $sql = "INSERT INTO BalanceplusView (Date,Name,PromotionText,Views,Country,Operator,Service,UniqueViews) VALUES('$date','$obj->Operator',?,$obj->Views,'$obj->Country','$obj->Operator1',?,$obj->UniqueViews)";

		  // echo $sql.PHP_EOL.PHP_EOL.'<br><br>';
           $sqlQuery =  $this->GetDbConnection()->prepare($sql);
			try {
				$sqlQuery->execute(array($teaserText,$service));
   
				} catch (Exception $e) {
   
						//echo $sql;
						//exit();
						
					}
           

        }

        $sql = "UPDATE  `BalanceplusView` SET Country = 'Belarus', Operator = 'Velcom' WHERE `Name`= 'BelarusVelcom';
                UPDATE `BalanceplusView` SET Country = 'Armenia' , Operator = 'Beeline' WHERE `Name`= 'ArmeniaBeeline';
                UPDATE `BalanceplusView` SET Country = 'Georgia' , Operator = 'Geocell' WHERE `Name`= 'GeorgiaGeocell';
                UPDATE `BalanceplusView` SET Country = 'Mauritiana' , Operator = 'Chinguitel' WHERE `Name`= 'Chinguitel';
                UPDATE `BalanceplusView` SET Country = 'Cameroon' , Operator = 'Nexttel' WHERE `Name`= 'CameroonNexttel';
                UPDATE `BalanceplusView` SET Country = 'Jordan' , Operator = 'Zain' WHERE `Name`= 'JordanZain';
                UPDATE `BalanceplusView` SET Country = 'Tadjikistan' , Operator = 'Megafon' WHERE `Name`= 'TajikistanMegafon';
                UPDATE `BalanceplusView` SET Country = 'Tadjikistan' , Operator = 'Beeline' WHERE `Name`= 'TajikistanBeeline';
                UPDATE `BalanceplusView` SET Country = 'Uzbekistan' , Operator = 'UMS' WHERE `Name`= 'UzbekistanUms';
                UPDATE `BalanceplusView` SET Country = 'Telemor' , Operator = 'Telemor' WHERE `Name`= 'Telemor';";

        $this->GetDbConnection()->query($sql);



    }



    function FetchStoredProc(ReportData $reportData, $callableprocudure)
    {
        $reportData->name = trim($reportData->name);

        $procudure = $reportData->name;

        $uniqueName = preg_replace('/[^A-Za-z0-9\-]/', '',$reportData->name);

        $date = date('ymdhis');


        $tableName = "@Table" . $date;

        $proc =  " --".$uniqueName."\n";

        $proc.= " Else If @Procudure  = '".$procudure."' \n BEGIN  \n";

        $tempTable = "DECLARE " . $tableName . " TABLE  ( \n";


        try {
            $result = mssql_query("EXEC $callableprocudure",$this->dbConnectionMssql);

            if (!$result) {

                die('MSSQL error: ' . mssql_get_last_message());
            }

            $obj = mssql_fetch_object($result);

        } catch (\Exception $e) {

            echo $e->getMessage();

            exit;
        }

      $count = 1;
        foreach (array_keys((array)$obj) as $column) {

            if (strtolower($column) == 'date' || strtolower($column) == 'datetime') {

                $tempTable .= $column . ' DATETIME, ' . "\n";

            }elseif(strtolower($column) == 'servicekey') {

                $tempTable .= $column.$count. ' VARCHAR(50),' . "\n";
            }

            else {

                $tempTable .= $column . ' VARCHAR(50),' . "\n";
            }

            $count++;
        }

        $tempTable = substr($tempTable, 0, -2);

        $tempTable = $tempTable . " \n ) \n";

        $tempTable .= "INSERT INTO " . $tableName . " EXEC " . $reportData->param;

        $tempTable .= "\nINSERT INTO [VCHReports].[dbo].[Unifun_Promo](Date,Country,Operator,Service,Inflow,Outflow,Activation) SELECT date,@Country,@Operator,@Service,CAST(NewSubscribersPayed AS INT) + CAST(NewSubscribersTrial AS INT) ,CAST(StoppedSubscribersTrial AS INT) + CAST(StoppedSubscribersPayed AS INT),UnicAbon FROM " . $tableName;

        $tempTable .= "\n END" ;

        $tempTable .=  "\n --".$uniqueName;

        $tempTable.= "\n\n\n --ADDNEWELSEIF";

        $p = fopen("storenew.txt", "w");

        fwrite($p, $proc . $tempTable);

        $this->OldProc();

        $this->AlterDataProcudure();

        $this->AddToSchedule($reportData);

        $this->Save($reportData);

        return true;

    }

    function OldProc()
    {

        $oldStoredProcudure = '';

        $result = mssql_query('sp_helptext [Unufun_Promo_DataCollection]',$this->dbConnectionMssql);

        while ($obj = mssql_fetch_object($result)) {

            $oldStoredProcudure .= $obj->Text;

            if ($obj->Text == '') {

                $oldStoredProcudure .= "\n";
            }

        }
        $p = fopen("storeOld.txt", "w");

        fwrite($p, $oldStoredProcudure);


    }
    function AlterDataProcudure(){


        $new = str_replace("--ADDNEWELSEIF", file_get_contents('storenew.txt'), str_replace('CREATE', 'ALTER', file_get_contents('storeOld.txt')));

        $pp = fopen("store.txt", "w");

        fwrite($pp, $new);

        $stmp = mssql_query( file_get_contents('store.txt'),$this->dbConnectionMssql);


        if (!$stmp OR $stmp == false) {

            die('MSSQL error: ' . mssql_get_last_message());
        }

    }

    function AddToSchedule(ReportData $reportData){

        $reportData->name = trim($reportData->name);

        $uniqueName = preg_replace('/[^A-Za-z0-9\-]/', '',$reportData->name);

        $this->GetOldSchedule();

        $newproc =  " --".$uniqueName."\n BEGIN TRY \n";

        $newproc.= "exec  [VCHReports].[dbo].[Unufun_Promo_DataCollection] '$reportData->country','$reportData->operator','$reportData->service','$reportData->name'";

        $newproc.=  "\n END TRY \n BEGIN CATCH \n END CATCH; \n--".$uniqueName;

        $newproc.= "\n --ADDNEWELSEIF";

        $new = str_replace("--ADDNEWELSEIF", $newproc, str_replace('CREATE', 'ALTER', file_get_contents('storescheduleOld.txt')));

        $pp = fopen("storeSchedule.txt", "w");

        fwrite($pp, $new);

        $stmp =  mssql_query(file_get_contents('storeSchedule.txt'),$this->dbConnectionMssql);

        if (!$stmp) {

            die('MSSQL error: ' . mssql_get_last_message());
        }



    }
    function  GetOldSchedule(){

        $oldStoredProcudure = '';

        $result = mssql_query('sp_helptext [RUN_UNIFUN_PROMO_REPORT]',$this->dbConnectionMssql);

        while ($obj = mssql_fetch_object($result)) {

            $oldStoredProcudure .= $obj->Text;

            if ($obj->Text == '') {

                $oldStoredProcudure .= "\n";
            }

        }
        $p = fopen("storescheduleOld.txt", "w");

        fwrite($p, $oldStoredProcudure);
    }


    function CheckProcudure($procudureName)
    {


        $result = mssql_query("SELECT name FROM sys.parameters WHERE object_id = OBJECT_ID('$procudureName')",$this->dbConnectionMssql);

        $paramName = array();

        while ($obj = mssql_fetch_object($result)) {

            array_push($paramName, $obj->name);

        }

        return $paramName;


    }

    function Save(ReportData $reportData)
    {
        try {

            $this->Insert($reportData, array("id"), "inflow_stored_procudure");

            return true;
        } catch (\Exception $e) {

            echo $e->getMessage();
            return false;
        }

    }

    function  DeleteFromProc(ReportData $reportData){

        $this->OldProc();

        $this->GetOldSchedule();

        $unique =  preg_replace('/[^A-Za-z0-9\-]/', '',$reportData->name);

        $string =  str_replace('CREATE', 'ALTER', file_get_contents('storeOld.txt'));

        $string  = preg_replace('/--'.$unique.'[\s\S]+?--'.$unique.'/', '', $string);

        $pp = fopen("storeOld.txt", "w");

        fwrite($pp, $string);

        mssql_query(file_get_contents('storeOld.txt'),$this->dbConnectionMssql);


        //for schedule

        $string =  str_replace('CREATE', 'ALTER', file_get_contents('storescheduleOld.txt'));

        $string  = preg_replace('/--'.$unique.'[\s\S]+?--'.$unique.'/', '', $string);

        $pp = fopen("storescheduleOld.txt", "w");

        fwrite($pp, $string);

        mssql_query(file_get_contents('storescheduleOld.txt'),$this->dbConnectionMssql);

        return $this->DeleteProcudurefromDB($reportData);

        //end of schedule
    }

    function DeleteProcudurefromDB($reportData)
    {

        $sql = " DELETE FROM inflow_stored_procudure where id = $reportData->id";

        $this->GetDbConnection()->query($sql);

        return true;
    }








}