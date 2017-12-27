<?php

class Crontab
{
    static public function addJob($job = '')
    {
        if (self::doesJobExist($job)) {
            return false;
        } else {
            $jobs = self::getJobs();
            $jobs[] = $job;
            return self::saveJobs($jobs);
        }
    }

    static public function doesJobExist($job = '')
    {
        $jobs = self::getJobs();
        if (in_array($job, $jobs)) {
            return true;
        } else {
            return false;
        }
    }

    static public function getJobs()
    {
        $output = shell_exec('crontab -l');
        return self::stringToArray($output);
    }

    static private function stringToArray($jobs = '')
    {
        $array = explode("\r\n", trim($jobs)); // trim() gets rid of the last \r\n
        foreach ($array as $key => $item) {
            if ($item == '') {
                unset($array[$key]);
            }
        }
        return $array;
    }

    static public function saveJobs($jobs = array())
    {

	//var_dump($jobs).'<br>';
	//echo self::arrayToString($jobs).'<br>';
	//exit;

        $output = shell_exec('echo "' . self::arrayToString($jobs) . '" | crontab -');
        return $output;
    }

    static private function arrayToString($jobs = array())
    {

        $string = implode("\r\n", $jobs);
        return $string;
    }

    static public function removeJob($job = '')
    {
        if (self::doesJobExist($job)) {
            $jobs = self::getJobs();
            unset($jobs[array_search($job, $jobs)]);
            return self::saveJobs($jobs);
        } else {
            return false;
        }
    }
}

function PrepareCronCommandFromID($job)
{
   
    $command = "10 08 * * * $job";

    return $command;

}

///$p = Crontab::getJobs();
//var_dump($p);

////$path = "http://demo.unifun.com/UnifunPromo/FetchData/FetchDataInflow";
///$path = "http://demo.unifun.com/UnifunPromo/FetchData/FetchDataBalancePlusView";

    //$cron = PrepareCronCommandFromID($path);
	
   
   //Crontab::addjob("30 10 * * * wget http://localhost/UnifunPromo/FetchData/FetchDataInflow");
    //Crontab::addJob("45 10 * * * wget http://localhost/UnifunPromo/FetchData/FetchDataBalancePlusView");
   // Crontab::removeJob("20 06 * * * wget demo.unifun.com/UnifunPromo/FetchData/FetchDataInflow 30 06 * * * wget demo.unifun.com/UnifunPromo/FetchData/FetchDataBalancePlusView");
    //Crontab::removeJob("20 08 * * * wget demo.unifun.com/UnifunPromo/FetchData/FetchDataInflow");
    //Crontab::removeJob("20 08 * * * wget demo.unifun.com/UnifunPromo/FetchData/FetchDataInflow");
	
   $p = Crontab::getJobs();
  


//var_dump($p);
foreach($p as $pp){
	
	//Crontab::removeJob("$pp");
    echo $pp.'<br>';
}
//Crontab::addJob("20 07 * * * wget http://localhost/UnifunPromo/FetchData/FetchDataBalancePlusView");

	//$p = shell_exec('crontab -l');
	//var_dump($p);
	

