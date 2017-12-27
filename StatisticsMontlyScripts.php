<?php

ini_set('memory_limit', '-1');

set_time_limit(0);

// Local Configuration
//$dbName = "balance_plus";
//$db = new \PDO("mysql:host=192.168.0.145;dbname={$dbName}", "root", "", array(\PDO::MYSQL_ATTR_LOCAL_INFILE => true));
//

// Live Configuration
$dbName = "balance_live_chinguitel";

$db = new \PDO("mysql:host=localhost;dbname={$dbName}", "root", "jsd67FGa", array(\PDO::MYSQL_ATTR_LOCAL_INFILE => true));

$db->exec("SET CHARACTER SET utf8");

$db->exec("SET NAMES utf8");

$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

$db->exec("USE $dbName;");

$timeQuery = "SELECT CAST(DATE_SUB(CURRENT_TIMESTAMP,INTERVAL 1 MONTH) AS DATE)  AS prevMonth";

$stmt = $db->prepare($timeQuery);

$stmt->execute();

$prevMonthDate = $stmt->fetch(PDO::FETCH_OBJ);

$date = new DateTime($prevMonthDate->prevMonth . " 00:00:01");

$interval = date_interval_create_from_date_string('1 hour');

$date = date_add($date, $interval);


$sql = "INSERT INTO sessions_archive (datetime, short_code, sessionID, subscriber, shank_id, is_activated, last_answer, last_input, dialogID,date,hour ) VALUES ";

$sql_statistics = "INSERT INTO statistic_archive (stamp, shank_id, count, region,date,hour) VALUES ";

$concatSQL = "";
$concatSQL_Statistics = "";

/*
$shankIDsQuery = "SELECT ID from messages_message where is_deleted=0 and is_history=0 and is_perm_deleted=0 ";


$shankStmt = $db->prepare($shankIDsQuery);

$shankStmt->execute();
$ShankIDArray = array();
while ($shankIDS = $shankStmt->fetch(PDO::FETCH_OBJ)) {
    $ShankIDArray[] = $shankIDS->ID;


}*/
$ShankIDArray=array(1,2);

//999
//551234567
//
for ($i = 0; $i < 240; $i++) {

    for ($j = 0; $j < 3; $j++) {

        $ShankIndex = array_rand($ShankIDArray);
        $ShankID = $ShankIDArray[$ShankIndex];

        $date = date_add($date, $interval);
        $shortCode = rand(200, 500);
        $sessionID = rand(9923363632, 9924363632);
        $subscriber = (int)"999". rand(851234567, 891934567);

        $shank_id = $ShankID;
        $is_activated = rand(0, 1);
        $last_answer = rand(0, 10);
        $last_input = rand(0, 10);
        $dialogID = rand(1, 600);
        $count = rand(1, 600);
        $region = rand(0, 5);


        if ($concatSQL == '') {

            $concatSQL .= "('{$date->format("Y-m-d H:i:s")}',$shortCode, $sessionID, $subscriber, $shank_id, $is_activated, $last_answer,$last_input, $dialogID,'{$date->format("Y-m-d")}','{$date->format("H:i:s")}')"; //. PHP_EOL;
        } else {

            $concatSQL .= ",('{$date->format("Y-m-d H:i:s")}',$shortCode, $sessionID, $subscriber, $shank_id, $is_activated, $last_answer,$last_input, $dialogID,'{$date->format("Y-m-d")}','{$date->format("H:i:s")}')"; //. PHP_EOL;
        }
        if ($concatSQL_Statistics == '') {

            $concatSQL_Statistics .= "('{$date->format("Y-m-d H:i:s")}',$shank_id, $count, $region,'{$date->format("Y-m-d")}','{$date->format("H:i:s")}')";// . PHP_EOL;

        } else {
            $concatSQL_Statistics .= ",('{$date->format("Y-m-d H:i:s")}',$shank_id, $count, $region,'{$date->format("Y-m-d")}','{$date->format("H:i:s")}')";// . PHP_EOL;        }
        }
    }
}

$sql .= $concatSQL;

$sql_statistics .= $concatSQL_Statistics;


echo $db->query($sql) ? "720 Data Inserted into sessions_archive Table " : " Data could not inserted into sessions_archive ";

echo '<br/>';

echo $db->query($sql_statistics) ? "720 Data Inserted into statistic_archive Table " : " Data could not inserted into statistic_archive ";





