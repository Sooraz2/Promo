<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 5/17/2015
 * Time: 12:28 PM
 */

namespace Repositories;


use Infrastructure\MessageCriterionType;
use Infrastructure\SessionVariables;
use Infrastructure\TeaserActivationLogTypes;
use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use WebInterface\Models\LoginUserLog;
use WebInterface\Models\MessageCriterion;
use WebInterface\Models\MessagePeriod;
use WebInterface\Models\Teaser;
use WebInterface\Models\TeaserActivationLog;
use WebInterface\Models\TeaserPlayPauseLog;

class TeaserTableRepository extends Repo
{
    private $table;

    private $refrenceList;


    function __construct()
    {
        $this->table = "messages_message";

        parent::__construct($this->table, "WebInterface\\Models\\Teaser");

    }

    public function Save(Teaser $teaser, MessageCriterion $messageCriterion, MessagePeriod $messagePeriod)
    {
        $this->GetDbConnection()->beginTransaction();
        try {

            $teaserId = $this->Insert($teaser, array("id"));

            // dd($teaserId);
            $messageCriterion->message_id = $teaserId;

            if ($messagePeriod->start != null) {
                $messagePeriod->message_id = $teaserId;
                $this->Insert($messagePeriod, array("id"), 'messages_periods');
            }

            $this->Insert($messageCriterion, array("id"), "messages_criterion");

            $this->GetDbConnection()->commit();

            return $teaserId;

        } catch (\Exception $e) {

            $this->GetDbConnection()->rollBack();

            throw $e;

            return 0;
        }
    }

    function CloneTeaser(Teaser &$teaser, $id, LoginUserLog $loginUserLog, $userId)
    {
        $this->dbConnection->beginTransaction();
        try {
            $teaser->id = $this->Insert($teaser, array("id"));

            $messageId = $this->dbConnection->lastInsertId();

            $sql = "INSERT INTO messages_criterion (message_id, criterion_type_id, criterion_id, show_status) SELECT $messageId, criterion_type_id, criterion_id, show_status FROM messages_criterion WHERE message_id = :id";
            $sthMessages = $this->dbConnection->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $sql = "INSERT INTO `messages_periods` (message_id, start, end, is_removed) SELECT $messageId, start, end, is_removed FROM messages_periods WHERE `message_id`=:id";
            $sthMessages = $this->dbConnection->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $this->Insert($loginUserLog, array("ID"), "login_user_logs");
            $this->dbConnection->commit();
            return true;
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            return false;
        }
    }

    public function TeaserHistoryOnEdit($id, $userId)
    {
        $this->GetDbConnection()->beginTransaction();
        try {
            $sql = "INSERT INTO messages_message_history (is_termless,teaser_id, is_history,text,stamp,counter,is_active,is_deleted,chars,lang,is_perm_deleted,is_interactiv,activation_code,service,is_high_priority,is_whitelist, updated_date, updated_by, history_date)
                    SELECT is_termless,'$id', '1', text,stamp,counter,0,is_deleted,chars,chars,is_perm_deleted,is_interactiv,activation_code,service,is_high_priority,is_whitelist, updated_date, :updatedBy, CURRENT_TIMESTAMP
                    FROM messages_message WHERE id=:id";
            $sthMessages = $this->GetDbConnection()->prepare($sql);
            $sthMessages->execute(array(":id" => $id, ":updatedBy" => $userId));

            $messageId = $this->GetDbConnection()->lastInsertId();

            $sql = "INSERT INTO messages_criterion_history (message_id, criterion_type_id, criterion_id, show_status) SELECT $messageId, criterion_type_id, criterion_id, show_status FROM messages_criterion WHERE message_id = :id";
            $sthMessages = $this->GetDbConnection()->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $sql = "INSERT INTO messages_periods_history (message_id, start, end, is_removed) SELECT $messageId, start, end, is_removed FROM messages_periods WHERE message_id = :id";
            $sthMessages = $this->GetDbConnection()->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $sql = "INSERT INTO teaser_activation_log_history (TeaserID, `Type`, `Timestamp`) SELECT $messageId, `Type`, `Timestamp` FROM teaser_activation_log WHERE TeaserID = :id";
            $sthMessages = $this->GetDbConnection()->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $this->GetDbConnection()->commit();

            return true;
        } catch (\Exception $e) {
            dd($e);
            $this->GetDbConnection()->rollBack();
            return false;
        }
    }

    public function updateTeaserText($teaserID, $text)
    {
        $this->GetDbConnection()->beginTransaction();
        try {
            $sql = "UPDATE messages_message SET text = :text WHERE id = :id";
            $statement = $this->GetDbConnection()->prepare($sql);
            $statement->execute(
                array(
                    ":text" => $text,
                    ":id" => $teaserID
                )
            );

            $this->GetDbConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->GetDbConnection()->rollBack();
            return false;
        }
    }

    public function SaveAdvance(Teaser $teaser, $messagePeriods, $messageCriterionList, $criterionRoaming = null, $changedTeaserLog = null, $changedTeaserDefaultLog = null, $launchedTeaser = null, $termlessTeaserLog = null)
    {
        $this->dbConnection->beginTransaction();

        try {
            if (count($messagePeriods) > 0) {

                $teaser->is_active = 1;

            } else {
                $teaser->is_active = 0;
            }


            $activationLog = new TeaserActivationLog();

            $activationLog->TeaserID = $teaser->id;

            $activationLog->Timestamp = $this->GetCurrentDateTime();

            if ($teaser->is_active == 1) {

                $activationLog->Type = TeaserActivationLogTypes::$ActiveOnTeaserCreation;

            } else {
                $activationLog->Type = TeaserActivationLogTypes::$InActiveOnTeaserCreation;
            }

            $this->UpdateTable($teaser, array("id"), "id");


            $this->dbConnection->query("UPDATE messages_periods SET is_removed=1 where message_id = {$teaser->id} AND start < CURRENT_DATE ");

            $this->dbConnection->query("DELETE FROM messages_periods where message_id = {$teaser->id} AND start >= CURRENT_DATE ");

            if (count($messagePeriods) > 0) {

                foreach ($messagePeriods as $messagePeriod) {
                    $this->Insert($messagePeriod, array('id'), 'messages_periods');
                }
            }

            $this->dbConnection->query("DELETE FROM messages_criterion where message_id={$teaser->id}");

            if (count($messageCriterionList) > 0) {

                foreach ($messageCriterionList as $messageCriterion) {

                    if ($messageCriterion->criterion_id != 0) {

                        $this->Insert($messageCriterion, array('id'), 'messages_criterion');
                    }
                }
            }

            $this->Insert($activationLog, array("ID"), "teaser_activation_log");


            $this->dbConnection->commit();

            $this->dbConnection->errorInfo();

            if (!is_null($changedTeaserLog))
                $this->Insert($changedTeaserLog, array("ID"), "login_user_logs");

            if (!is_null($changedTeaserDefaultLog))
                $this->Insert($changedTeaserDefaultLog, array("ID"), "login_user_logs");

            if (!is_null($launchedTeaser))
                $this->Insert($launchedTeaser, array("ID"), "login_user_logs");

            if (!is_null($termlessTeaserLog))
                $this->Insert($termlessTeaserLog, array("ID"), "login_user_logs");

            return true;

        } catch (\Exception $e) {
            dd($e);
            $this->dbConnection->rollBack();

//            throw $e;

            return false;
        }
    }

    public function Update(Teaser $teaser, $changedTeaserLog = null, $changedTeaserDefaultLog = null, $launchedTeaser = null, $termlessTeaserLog = null)
    {
        try {
            $this->UpdateTable($teaser, array("id"), "id");
            if (!is_null($changedTeaserLog))
                $this->Insert($changedTeaserLog, array("ID"), "login_user_logs");

            if (!is_null($changedTeaserDefaultLog))
                $this->Insert($changedTeaserDefaultLog, array("ID"), "login_user_logs");

            if (!is_null($launchedTeaser))
                $this->Insert($launchedTeaser, array("ID"), "login_user_logs");

            if (!is_null($termlessTeaserLog))
                $this->Insert($termlessTeaserLog, array("ID"), "login_user_logs");

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function UpdateStatus($teaser_id, $status, TeaserActivationLog $activationLog = null, LoginUserLog $loginUserLog = null)
    {
        $this->GetDbConnection()->beginTransaction();
        try {
            if (!is_null($activationLog)) {
                $sql = "UPDATE `teaser_activation_log` SET `IsLatest` = 0 WHERE TeaserID = :teaserID AND `Date` = :Date ";
                $statement = $this->GetDbConnection()->prepare($sql);
                $statement->execute(
                    array(
                        ":teaserID" => $teaser_id,
                        ":Date" => $this->GetCurrentDate()
                    )
                );

                $sql = "UPDATE `teaser_activation_log` SET `IsLatestGroup` = 0,`History` = 1 WHERE TeaserID = :teaserID";
                $statement = $this->GetDbConnection()->prepare($sql);
                $statement->execute(
                    array(
                        ":teaserID" => $teaser_id
                    )
                );

                $ActivationId = $this->Insert($activationLog, array("ID"), "teaser_activation_log");

                $sql = "INSERT INTO `messages_criterion_history` (message_id, criterion_type_id, criterion_id, show_status) SELECT :ActivationId, criterion_type_id, criterion_id, show_status FROM messages_criterion WHERE message_id = :message_id";
                $statement = $this->GetDbConnection()->prepare($sql);
                $statement->execute(
                    array(
                        ":ActivationId" => $ActivationId,
                        ":message_id" => $teaser_id
                    )
                );
            }

            switch ($status) {
                case 0:
                    $sth = $this->GetDbConnection()->prepare("UPDATE {$this->table} SET is_active= :status, paused_date=CURRENT_TIMESTAMP WHERE id= :teaserId");
                    $bindParams = array(":status" => $status, ":teaserId" => $teaser_id);
                    break;
                case 1:
                    $sth = $this->GetDbConnection()->prepare("UPDATE {$this->table} SET is_active= :status, paused_date=NULL  WHERE id= :teaserId");
                    $bindParams = array(":status" => $status, ":teaserId" => $teaser_id);
                    break;
            }

            $sth->execute($bindParams);

            if (!is_null($loginUserLog))
                $this->Insert($loginUserLog, array("ID"), "login_user_logs");

            $this->GetDbConnection()->commit();
            return true;
        } catch (\Exception $e) {
            dd($e);
            $this->GetDbConnection()->rollBack();
            return false;
        }
    }

    function UpdatePriority($teaser_id, $priority, LoginUserLog $loginUserLog)
    {
        $this->GetDbConnection()->beginTransaction();
        try {

            if ($priority == 0) {
                $this->GetDbConnection()->query("UPDATE {$this->table} SET is_high_priority=NULL WHERE id=$teaser_id");
            } else {
                $this->GetDbConnection()->query("UPDATE {$this->table} SET is_high_priority=$priority WHERE id=$teaser_id");
            }

            $ActivationLog = $this->GetLastActivationLog($teaser_id);

            $sql = "UPDATE `teaser_activation_log` SET `IsLatest` = 0 WHERE TeaserID = :teaserID AND `Date` = :Date ";
            $statement = $this->GetDbConnection()->prepare($sql);
            $statement->execute(
                array(
                    ":teaserID" => $teaser_id,
                    ":Date" => $this->GetCurrentDate()
                )
            );

            $sql = "UPDATE `teaser_activation_log` SET `IsLatestGroup` = 0,`History` = 1 WHERE TeaserID = :teaserID";
            $statement = $this->GetDbConnection()->prepare($sql);
            $statement->execute(
                array(
                    ":teaserID" => $teaser_id
                )
            );

            $date = $this->GetCurrentDateTime();
            foreach($ActivationLog as $log){
                /**
                 * @var $log TeaserActivationLog
                 * */
                $log->PreviousUpdateOn = $log->Timestamp;
                $log->Timestamp = $date;
                $log->ID = null;
                $log->Date = $this->GetDateOrTime($date, "date");
                $log->Time = $this->GetDateOrTime($date, "time");
                $log->Priority = $priority == 0 ? null : $priority;
                $log->TeaserType = $priority == 0 ? "Other" : "Priority";
                $log->IsLatest = 1;
                $log->IsLatestGroup = 1;
                $ActivationId = $this->Insert($log, array("id"), "teaser_activation_log");

                $sql = "INSERT INTO `messages_criterion_history` (message_id, criterion_type_id, criterion_id, show_status) SELECT :ActivationId, criterion_type_id, criterion_id, show_status FROM messages_criterion WHERE message_id = :message_id";
                $statement = $this->GetDbConnection()->prepare($sql);
                $statement->execute(
                    array(
                        ":ActivationId" => $ActivationId,
                        ":message_id" => $teaser_id
                    )
                );
            }

            $this->Insert($loginUserLog, array("ID"), "login_user_logs");

            $this->GetDbConnection()->commit();
            return true;
        } catch (\Exception $e) {
            $this->GetDbConnection()->rollBack();
            return false;
        }
    }

    public function GetLastActivationLog($teaserIds){
        $where = "1=1";

        if(!is_null($teaserIds) && is_array($teaserIds))
            $where = " tbl.`TeaserID` IN (".implode(',', $teaserIds).") ";
        else if(!is_null($teaserIds))
            $where = " tbl.`TeaserID` = '{$teaserIds}' ";

        $sql = "SELECT
                  *
                FROM
                  `teaser_activation_log` tbl
                WHERE IsLatestGroup = '1' AND {$where}";

        $statement = $this->GetDbConnection()->prepare($sql);
        $statement->execute();

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $ActivationLogs = array();
        if(count($data) > 0){
            foreach($data as $log){
                $ActivationLog = new TeaserActivationLog();
                $ActivationLog->MapParameters($log);
                $ActivationLogs[] = $ActivationLog;
            }
        }

        return $ActivationLogs;
    }

    function FindAll(AjaxGrid $ajaxGrid, $serviceName)
    {
        extract($_GET);
        $condition = "WHERE 1 = 1 AND is_deleted=0 AND is_history=0";
        if (isset($search) && !empty($search) and $search = trim($search)) {
            $condition .= " AND (`text` LIKE '%$search%'  OR `id`= '$search')";
        }

        $criteriaScript = $this->GetCriteriaScript();

        $messageCriterionType = MessageCriterionType::Service;

        $serviceString = " 1=1";
        if (!is_null($serviceName) && $serviceName != "")
            $serviceString = "temp.service_name = '$serviceName'";


        $sql = <<<SQL
        SELECT *, has_history as is_modified FROM (
        SELECT *, DATE_FORMAT(`stamp`,'%d-%m-%Y %H:%i:%s') AS DateCreated, CHAR_LENGTH (text) `NumberOfSymbols`,
        CASE WHEN ( (SELECT count(*) FROM teaser_activation_log WHERE teaser_activation_log.TeaserID = messageOut.id and History = 1) > 0 OR messageOut.reference_id IS NOT NULL )
        THEN '1'
        ELSE '0'
        END AS has_history,
        (SELECT service_name FROM service_options WHERE id = (SELECT criterion_id FROM `messages_criterion` WHERE message_id = messageOut.id AND criterion_type_id = {$messageCriterionType})) as service_name,
        CASE
            WHEN
            CURRENT_TIMESTAMP > (SELECT MAX(`end`) FROM `messages_periods` WHERE message_id=messageOut.`id` AND is_removed= 0)
            THEN 'Finished'
            ELSE 'Ongoing'
        END AS PublicationStatus,
        CASE WHEN CURRENT_TIMESTAMP <= (SELECT MAX(`end`) FROM `messages_periods` WHERE message_id=messageOut.`id` AND is_removed= 0) AND is_active = 1
            THEN 1
            ELSE 0
        END AS PlayPauseStatus,

         $criteriaScript,
        CASE WHEN messageOut.stamp='1000-01-01 00:00:00'
        THEN '1'
        ELSE '0'
        END AS DefaultTeaser,
        (SELECT `Username` FROM `login_user` WHERE id = messageOut.updated_by) as Username
        FROM messages_message AS messageOut $condition ) temp
        WHERE $serviceString
         ORDER BY DefaultTeaser DESC,
SQL;


        if ($ajaxGrid->advanceSorting == null) {
            $sql .= " $ajaxGrid->sortExpression $ajaxGrid->sortOrder ";
        } else {
            $sql .= "{$ajaxGrid->advanceSorting}";
        }

        $sql .= " LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);


        if (!is_null($serviceName) && $serviceName != "") {
            $totalSql = <<<SQL
                        SELECT 
                          COUNT(*) 
                        FROM
                          messages_message 
                          $condition  AND
                        
                         id IN 
                          (SELECT 
                            message_id 
                          FROM
                            messages_criterion 
                          WHERE criterion_type_id = {$messageCriterionType}
                            AND criterion_id = 
                            (SELECT 
                              id 
                            FROM
                              service_options 
                            WHERE service_name = '{$serviceName}'))                           

SQL;


            $sqlQuery = $this->GetDbConnection()->query($totalSql);
        }else {
            $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM {$this->table} temp $condition");
        }
        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;
        $list['Header'] = true;

        return $list;
    }

    function GetPlannedTimesByMessageID($messageId)
    {
        $sql = "SELECT * FROM `messages_periods` WHERE `message_id`=:id";
        $sth = $this->dbConnection->prepare($sql);
        $sth->execute(array(":id" => $messageId));
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }


    function GetCriterionByTypeAndMessageID($criterionType, $messageId)
    {
        $oMessageCriterionType = new \ReflectionClass('Infrastructure\MessageCriterionType');

        $criterionTypeId = $oMessageCriterionType->getConstant($criterionType);

        $MessageCriterionType = array("TimeCriteria" => "criterion_time",
            "SubBalance" => "criterion_balance",
            "ValidTill" => "criterion_valid",
            "SubRegion" => "criterion_region",
            "MsisdnPrefix" => "criterion_dfn",
            "TariffPlan" => "criterion_packet",
            "LastRecharge" => "last_recharge",
            "PaidActions" => "paid_actions",
            "RoamingCriteria" => "criterion_roaming",
            "USSDShortNumbers" => "criterion_ussd_short_number",
            "OptionActivationCheck" => "activation_links",
            'BonusesBalance' => "bonuses_balance",
            'SubList' => "criterion_subscribers_group",
            'SubscriberClub' => "subscriber_club",
            'ActiveServices' => "criterion_services"
        );

        $sql = "SELECT t.*,'$criterionType' as `CriterionType` FROM  `messages_criterion` as mc
                INNER JOIN `{$MessageCriterionType["$criterionType"]}` t ON mc.criterion_id = t.id
                WHERE mc.`criterion_type_id`=:criterionTypeId and mc.message_id= :messageId";

        $sth = $this->GetDbConnection()->prepare($sql);
        $sth->execute(array(":criterionTypeId" => $criterionTypeId,
            ":messageId" => $messageId
        ));
        $res = $sth->fetchAll(\PDO::FETCH_ASSOC);

        $col = array();

        foreach ($res as $resField) {

            array_walk($resField, function (&$item, $key) {
                $item = is_null($item) ? "null : " . $key : $item;
            });

            global $langConfig;
            $arr = $resField;
            $newArr = array();

            foreach ($arr as $key => $val) {
                if (property_exists($langConfig->languageClass, $key))
                    $newArr[$langConfig->languageClass->$key] = $val;
            }

            array_push($col, $newArr);

        }

        return $col;
    }


    function FindAllActiveTeaser(AjaxGrid $ajaxGrid)
    {
        extract($_GET);
        //$condition = "WHERE 1 = 1 AND messageOut.is_active=1 AND messageOut.is_deleted=0 AND CAST(MP.start AS Date)=CURRENT_DATE ";
        $condition = "WHERE 1 = 1 AND messageOut.is_active=1 AND messageOut.is_history=0 AND messageOut.is_deleted=0 AND ((messageOut.stamp!='1000-01-01 00:00:00' AND CURRENT_DATE BETWEEN  CAST(MP.start AS Date)
        AND CAST(MP.end AS Date) )
         OR messageOut.stamp='1000-01-01 00:00:00') ";

        if (isset($search) && !empty($search) and $search = trim($search)) {
            $condition .= " AND (`text` LIKE '%$search%' OR messageOut.id= '$search')";
        }

        $criteriaScript = $this->GetCriteriaScript();

        $sql = <<<SQL

        SELECT messageOut.*,DATE_FORMAT(messageOut.`stamp`,'%d-%m-%Y %H:%i:%s') AS DateCreated, $criteriaScript,
        CASE WHEN messageOut.stamp='1000-01-01 00:00:00'
        THEN '1'
        ELSE '0'
        END AS DefaultTeaser,
        CASE
            WHEN
            CURRENT_TIMESTAMP > (SELECT MAX(`end`) FROM `messages_periods` WHERE message_id=messageOut.`id`)
            THEN 'Finished'
            ELSE 'Ongoing'
        END AS PublicationStatus,
        CASE WHEN is_active=1 THEN 1
        WHEN is_active=0 AND (select max(start) FROM messages_periods WHERE message_id=messageOut.id) < CURRENT_DATE THEN 1 ELSE 0
        END is_active,
        (SELECT `Username` FROM `login_user` WHERE id = messageOut.updated_by) as Username
        FROM messages_message AS messageOut
        Left JOIN  `messages_periods` MP ON MP.message_id=messageOut.id
        $condition GROUP BY messageOut.id ORDER BY DefaultTeaser DESC ,

SQL;
        if ($ajaxGrid->advanceSorting == null) {
            $sql .= " $ajaxGrid->sortExpression $ajaxGrid->sortOrder ";
        } else {
            $sql .= "{$ajaxGrid->advanceSorting}";
        }

        $sql .= " ,text ASC ";

        $sql .= " LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber"; //echo ($sql); exit;


        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM {$this->table} as messageOut LEFT JOIN  `messages_periods` MP ON MP.message_id=messageOut.id $condition");

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;
        $list['Header'] = true;

        return $list;
    }

    public function GetTeaserStatus($teaserId)
    {
        $sql = "SELECT is_active FROM {$this->table} WHERE id=$teaserId";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetch();

        return $data[0];
    }

    public function CheckIfMessagePeriodExist($teaser)
    {
        $sql = "SELECT Count(*) FROM messages_periods WHERE message_id=$teaser";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetch();

        return $data[0] > 0 ? true : false;
    }

    function GetCriteriaScript()
    {
        $base_url = BASE_URL;
        global $langConfig;
        $language = $langConfig->languageClass;

        foreach ($language as $key => $value) {
            $value = func_mysql_escape_string($value);
            $language->$key = $value;
        }

        $sql = <<<SQL
      CASE WHEN chars=1
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->EnglishLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_en.png"/></button>'
              WHEN chars=2
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->RussianLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_ru.png"/></button>'
              WHEN chars=3
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->FrenchLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_fr.png"/></button>'
              WHEN chars=4
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->GeorgianLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_ge.png"/></button>'
               WHEN chars=5
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->TajikLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_tj.png"/></button>'
              WHEN chars=7
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->ArabicLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_zj.png"/></button>'
              ELSE ''
        END AS LanguageCriteria,
        CASE WHEN (SELECT Count(id) from `messages_criterion` WHERE `criterion_type_id`=1 and message_id=messageOut.id)>0
             THEN '<button class="btn btn-circle nobtndesign onhovertooltip" data-criterion-type="TimeCriteria" title="$language->TimeCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_time.png"/></span></button>'
             ELSE ''
        END AS TimeCriteria,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=3 AND message_id=messageOut.id)>0
             THEN '<button class="btn btn-circle nobtndesign onhovertooltip" data-criterion-type="ValidTill" title="$language->BalanceValidityCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_valid_till.png"/></button>'
             ELSE ''
        END AS ValidTill,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=2 AND message_id=messageOut.id)>0
             THEN '<button class="btn btn-circle nobtndesign onhovertooltip" data-criterion-type="SubBalance" title="$language->SubscribersBalanceCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_balance.png"/></button>'
             ELSE ''
        END AS SubscriberBalance,
        CASE WHEN (SELECT COUNT(id) FROM `criterion_roaming` WHERE TeaserID=messageOut.id AND `Value`='Yes')>0
             THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="RoamingCriteria" title="$language->RoamingCriteria" style="margin-right: 5px"><i class="fa fa-mobile"><span>R</span></i></button>'
             ELSE ''
        END AS RoamingCriteria,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=4 AND message_id=messageOut.id)>0
             THEN '<button class="btn btn-circle nobtndesign onhovertooltip" data-criterion-type="SubRegion" title="$language->SubscribersRegion" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_region.png"/></button>'
             ELSE ''
        END AS SubscriberRegion,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=6 AND message_id=messageOut.id)>0
             THEN '<button class="btn btn-circle nobtndesign onhovertooltip" data-criterion-type="TariffPlan" title="$language->TariffCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_tarif.png"/></button>'
             ELSE ''
        END AS TariffPlan,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=5 AND message_id=messageOut.id)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="MsisdnPrefix" title="$language->MsisdnPrefix" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_prefix.png"/></button>'
             ELSE ''
        END AS MsisdnPrefix,
        CASE  WHEN is_termless=1
             THEN '<button class="btn nobtndesign onhovertooltip" title="$language->Termless" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_termless.png"/></button>'
             ELSE ''
        END AS Termless,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=15 AND message_id=messageOut.id)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="BonusesBalance" title="$language->BonusesBalance" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_bonus.png"/></button>'
             ELSE ''
        END AS Bonus,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=14 AND message_id=messageOut.id)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="PaidActions" title="$language->LastCall" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_last_call.png"/></button>'
             ELSE ''
        END AS Payment,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=13 AND message_id=messageOut.id)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="LastRecharge" title="$language->LastRefill" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_refill.png"/></button>'
             ELSE ''
        END AS Refill,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=12 AND message_id=messageOut.id)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="SubList" title="$language->SubscriberList" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_subscriber_list.png"/></button>'
             ELSE ''
        END AS SubscriberList,
         CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=16 AND message_id=messageOut.id AND show_status IS NOT NULL)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="ActiveServices" title="$language->ActiveServices" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_active_services.png"/></button>'
             ELSE ''
        END AS ActiveServices,
         CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=17 AND message_id=messageOut.id)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="SubscriberClub" title="$language->SubscriberClub" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_subscriber_club.png"/></button>'
             ELSE ''
        END AS SubscriberClub,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion` WHERE `criterion_type_id`=19 AND message_id=messageOut.id)>0
             THEN '<button class="btn nobtndesign onhovertooltip" data-criterion-type="Roaming" title="$language->RoamingServices" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/roaming_criteria.png"/></button>'
             ELSE ''
        END AS RoamingServices

SQL;

        return $sql;

    }

    function GetCriteriaScriptHistory()
    {
        $base_url = BASE_URL;
        global $langConfig;
        $language = $langConfig->languageClass;

        foreach ($language as $key => $value) {
            $value = func_mysql_escape_string($value);
            $language->$key = $value;
        }

        $sql = <<<SQL
      CASE WHEN Language=1
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->EnglishLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_en.png"/></button>'
              WHEN Language=2
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->RussianLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_ru.png"/></button>'
              WHEN Language=3
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->FrenchLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_fr.png"/></button>'
              WHEN Language=4
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->GeorgianLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_ge.png"/></button>'
               WHEN Language=5
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->TajikLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_tj.png"/></button>'
              WHEN Language=7
              THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="LanguageCriteria" title="$language->ArabicLanguageCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_language_zj.png"/></button>'
              ELSE ''
        END AS LanguageCriteria,
        CASE WHEN (SELECT Count(id) from `messages_criterion_history` WHERE `criterion_type_id`=1 and message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn btn-circle nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="TimeCriteria" title="$language->TimeCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_time.png"/></span></button>')
             ELSE ''
        END AS TimeCriteria,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=3 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn btn-circle nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="ValidTill" title="$language->BalanceValidityCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_valid_till.png"/></button>')
             ELSE ''
        END AS ValidTill,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=2 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn btn-circle nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="SubBalance" title="$language->SubscribersBalanceCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_balance.png"/></button>')
             ELSE ''
        END AS SubscriberBalance,
        CASE WHEN (SELECT COUNT(id) FROM `criterion_roaming` WHERE TeaserID=`logs`.ID AND `Value`='Yes')>0
             THEN '<button class="btn btn-circle nobtndesign" data-criterion-type="RoamingCriteria" title="$language->RoamingCriteria" style="margin-right: 5px"><i class="fa fa-mobile"><span>R</span></i></button>'
             ELSE ''
        END AS RoamingCriteria,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=4 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn btn-circle nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="SubRegion" title="$language->SubscribersRegion" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_region.png"/></button>')
             ELSE ''
        END AS SubscriberRegion,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=6 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn btn-circle nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="TariffPlan" title="$language->TariffCriteria" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_tarif.png"/></button>')
             ELSE ''
        END AS TariffPlan,
        CASE WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=5 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="MsisdnPrefix" title="$language->MsisdnPrefix" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_prefix.png"/></button>')
             ELSE ''
        END AS MsisdnPrefix,
        CASE  WHEN TeaserType='Termless'
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" title="$language->Termless" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_termless.png"/></button>')
             ELSE ''
        END AS Termless,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=15 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="BonusesBalance" title="$language->BonusesBalance" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_bonus.png"/></button>')
             ELSE ''
        END AS Bonus,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=14 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="PaidActions" title="$language->LastCall" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_last_call.png"/></button>')
             ELSE ''
        END AS Payment,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=13 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="LastRecharge" title="$language->LastRefill" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_refill.png"/></button>')
             ELSE ''
        END AS Refill,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=12 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="SubList" title="$language->SubscriberList" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_subscriber_list.png"/></button>')
             ELSE ''
        END AS SubscriberList,
         CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=16 AND message_id=`logs`.ID AND show_status IS NOT NULL)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="ActiveServices" title="$language->ActiveServices" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_active_services.png"/></button>')
             ELSE ''
        END AS ActiveServices,
         CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=17 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="SubscriberClub" title="$language->SubscriberClub" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/criteria_subscriber_club.png"/></button>')
             ELSE ''
        END AS SubscriberClub,
        CASE  WHEN (SELECT COUNT(id) FROM `messages_criterion_history` WHERE `criterion_type_id`=19 AND message_id=`logs`.ID)>0
             THEN CONCAT('<button class="btn nobtndesign onhovertooltip history" data-message-id="', `logs`.ID, '" data-criterion-type="Roaming" title="$language->RoamingServices" style="margin-right: 5px"><img class="languageIcon" src="$base_url/includes/teaser/roaming_criteria.png"/></button>')
             ELSE ''
        END AS RoamingServices

SQL;

        return $sql;

    }

    public function FindAllArchiveTeaser(AjaxGrid $ajaxGrid, $search)
    {
        $condition = "WHERE 1 = 1 AND is_deleted=1 and is_perm_deleted !=1 ";

        if (isset($search) && !empty($search) and $search = trim($search)) {
            $condition .= " AND (`text` LIKE '%$search%' OR `messageOut`.id= '$search')";
        }
        $criteriaScript = $this->GetCriteriaScript();

        $sql = <<<SQL

        SELECT *, messageOut.id as MessageID, $criteriaScript

        FROM messages_message AS messageOut $condition ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber

SQL;

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM {$this->table} messageOut $condition");

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;

    }

    public function ArchiveRestoreTeaser($teaserId, $status, TeaserActivationLog $activationLog, LoginUserLog $deletedLog)
    {
        $this->dbConnection->beginTransaction();

        try {
            $userId = $_SESSION[SessionVariables::$UserID];

            $sql = "UPDATE `teaser_activation_log` SET `IsLatest` = 0 WHERE TeaserID = :teaserID AND `Date` = :Date ";
            $statement = $this->GetDbConnection()->prepare($sql);
            $statement->execute(
                array(
                    ":teaserID" => $teaserId,
                    ":Date" => $this->GetCurrentDate()
                )
            );

            $sql = "UPDATE `teaser_activation_log` SET `IsLatestGroup` = 0,`History` = 1 WHERE TeaserID = :teaserID";
            $statement = $this->GetDbConnection()->prepare($sql);
            $statement->execute(
                array(
                    ":teaserID" => $teaserId
                )
            );

            $this->Insert($activationLog, array("ID"), 'teaser_activation_log');
            $this->Insert($deletedLog, array("ID"), 'login_user_logs');

            $referenceIds = $this->GetAllChilds($teaserId);
            $referenceIds = implode(",", $referenceIds);

            $sth = $this->dbConnection->prepare("UPDATE messages_message SET is_deleted=$status, is_active=0, removed_by={$userId}, removal_date=CURRENT_TIMESTAMP WHERE id in ($referenceIds)");

            $sth->execute();

            $this->dbConnection->commit();
            return true;
        } catch (\Exception $e) {
            $this->dbConnection->rollBack();
            return false;
        }
    }

    function GetAllActiveTeaserCount()
    {
        $sqlQuery = $this->dbConnection->query("SELECT COUNT(DISTINCT MM.id) FROM {$this->table} MM LEFT JOIN messages_periods MP ON MM.id=MP.message_id WHERE is_active=1 AND is_deleted=0 AND is_history=0
                                                AND ((MM.stamp!='1000-01-01 00:00:00' AND CURRENT_DATE BETWEEN  CAST(MP.start AS Date) AND CAST(MP.end AS Date) AND MP.`is_removed` = 0 ) OR MM.stamp='1000-01-01 00:00:00')");
        $ActiveTeaserCount = $sqlQuery->fetchColumn();
        $_SESSION["ActiverTeaserCount"] = $ActiveTeaserCount;
        $sqlQuery = $this->dbConnection->query("SELECT COUNT(*) FROM {$this->table} WHERE is_deleted=0  AND is_history=0 ");
        $AllTeaserCount = $sqlQuery->fetchColumn();
        $_SESSION["TotalTeaserCount"] = $AllTeaserCount;
    }

    function GetAllActiveTeasers()
    {
        $sqlQuery = $this->dbConnection->query("SELECT DISTINCT MM.id FROM {$this->table} MM LEFT JOIN messages_periods MP ON MM.id=MP.message_id WHERE is_active=1 AND is_deleted=0 AND is_history=0
                                                    AND ((MM.stamp!='1000-01-01 00:00:00' AND CURRENT_DATE BETWEEN  CAST(MP.start AS Date) AND CAST(MP.end AS Date) AND MP.`is_removed` = 0 ) OR MM.stamp='1000-01-01 00:00:00')");

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
    }

    function GetDefaultTeaser($id = null)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE is_deleted=0 AND is_history=0 && `stamp`='1000-01-01 00:00:00'";
        if ($id != null) {
            $sql .= " &&  id !={$id}";
        }
        $sqlQuery = $this->dbConnection->query($sql);
        $DefaultTeaserCount = $sqlQuery->fetchColumn();

        return $DefaultTeaserCount;
    }

    function GetAllDefaultTeaser($id = null)
    {
        $sql = "SELECT * FROM `messages_message` WHERE stamp = '1000-01-01 00:00:00' AND is_deleted='0' AND is_perm_deleted = '0' AND is_history='0'";


        $sqlQuery = $this->dbConnection->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        return $data;
    }

    function checkDefaultTeaser($id = null)
    {
        if ($id != null) {
            $sqlQuery = $this->dbConnection->query("SELECT COUNT(*) FROM {$this->table} WHERE is_deleted=0 && `stamp`='1000-01-01 00:00:00' && `id` = '$id' ");

            $DefaultTeaserCount = $sqlQuery->fetchColumn();

            return ((int)$DefaultTeaserCount) > 0 ? true : false;
        }
    }

    function LogicalDelete($id)
    {
        $sql = "UPDATE {$this->table} SET is_perm_deleted=1 WHERE `id`=:id";

        $sth = $this->dbConnection->prepare($sql);

        return ($sth->execute(array(":id" => $id)));
    }

    function DeleteAll()
    {
        $sql = "UPDATE {$this->table} SET is_perm_deleted=1 WHERE is_deleted=1";

        $sth = $this->dbConnection->prepare($sql);

        return ($sth->execute());
    }

    function DeleteSelectedItem($delId)
    {
        $sql = "";
        foreach ($delId as $id) {
            $sql .= "UPDATE {$this->table} SET is_perm_deleted=1 WHERE is_deleted=1 and id IN($id); ";
        }
        $sth = $this->dbConnection->prepare($sql);

        return ($sth->execute());
    }

    function TeaserHistory(AjaxGrid $ajaxGrid, $TeaserID = null)
    {
        extract($_GET);
        $condition = " 1 = 1 ";
        if (isset($search) && !empty($search) and $search = trim($search)) {
            $condition .= " AND (`text` LIKE '%$search%'  OR messageOut.`id`= '$search')";
        }

        $this->refrenceList = array();
        $this->getChildRows($TeaserID);
        array_push($this->refrenceList, $TeaserID);
        $this->refrenceList = implode(",", $this->refrenceList);

        $criteriaScriptHistory = $this->GetCriteriaScriptHistory();

        $messageCriterionType = MessageCriterionType::Service;

        $sql = <<<SQL
                SELECT
                  TeaserID as id,
                  logs.`Text` AS `text`,
                  login_user.Username AS updated_by,
                  `Timestamp` AS updated_date,
                  ServiceName AS service_name,
                  {$criteriaScriptHistory}
                FROM
                  `teaser_activation_log` `logs`
                  LEFT JOIN login_user
                    ON login_user.ID = logs.`UpdatedBy`
                WHERE $condition AND logs.TeaserID IN ({$this->refrenceList}) ORDER BY {$ajaxGrid->sortExpression} {$ajaxGrid->sortOrder},

SQL;

        if ($ajaxGrid->advanceSorting == null) {
            $sql .= " $ajaxGrid->sortExpression $ajaxGrid->sortOrder ";
        } else {
            $sql .= "{$ajaxGrid->advanceSorting}";
        }

        $sqlLimit = " LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber  ";

        $sqlQuery = $this->GetDbConnection()->query($sql . $sqlLimit);
        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT COUNT(*) FROM ( $sql ) as t1 ");

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;
        $list['Header'] = true;
        $list['CurrentTeaser'] = $TeaserID;

        return $list;
    }

    function getChildRows($teaser_id)
    {
        $sql = $this->dbConnection->query("SELECT * FROM `messages_message` as messageOut WHERE `id` =$teaser_id");
        $row = $sql->fetch();

        if (!is_null($row['reference_id'])) {
            array_push($this->refrenceList, $row['reference_id']);
            $this->getChildRows($row['reference_id']);
        }
    }

    /**
     * Update teaser that has got updated text
     * @param id : Id of the Teaser
     * @param newText : Updated Text
     * @return New id of the updated Teaser
     * */
    function updateTeaserWithNewText($id, $newText, $user_id, LoginUserLog $loginUserLog = null, $newData)
    {
        try {
            $this->dbConnection->beginTransaction();

            $sql = "INSERT INTO messages_message (
                      text,
                      stamp,
                      t_counter,
                      counter,
                      is_active,
                      is_deleted,
                      chars,
                      lang,
                      is_perm_deleted,
                      is_interactiv,
                      activation_code,
                      service,
                      is_high_priority,
                      is_whitelist,
                      updated_by,
                      is_termless,
                      send_sms,
                      sms_text,
                      created_by
                      )
                    SELECT
                       '$newText',
                      stamp,
                      t_counter,
                      counter,
                      is_active,
                      is_deleted,
                      chars,
                      lang,
                      is_perm_deleted,
                      :is_interactiv,
                      :activationCode,
                      :service,
                      :priority,
                      :whitelist,
                      '$user_id',
                      :termLess,
                      send_sms,
                      sms_text,
                      '{$user_id}'
                    FROM messages_message WHERE id=:id";

            $sthMessages = $this->dbConnection->prepare($sql);
            $newData[':id'] = $id;
            $sthMessages->execute($newData);

            $messageId = $this->dbConnection->lastInsertId();


            $sql = "INSERT INTO `messages_periods` (`message_id`,`start`,`end`) SELECT $messageId, `start`,`end` FROM `messages_periods` WHERE `message_id` = :id";
            $sthMessages = $this->dbConnection->prepare($sql);
            $sthMessages->bindParam(':id', $id);
            $sthMessages->execute();

            $sql = "INSERT INTO messages_criterion (message_id, criterion_type_id, criterion_id) SELECT $messageId, criterion_type_id, criterion_id FROM
                  messages_criterion WHERE message_id = :id";
            $sthMessages = $this->dbConnection->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $sql = "INSERT INTO criterion_roaming (TeaserID, Value) SELECT $messageId, Value FROM criterion_roaming WHERE `TeaserID`=:id";
            $sthMessages = $this->dbConnection->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $sql = "UPDATE `messages_message` SET `reference_id` = :oldId, is_modified=1, updated_date=CURRENT_TIMESTAMP  WHERE id=:id";
            $sthMessages = $this->dbConnection->prepare($sql);
            $sthMessages->execute(array(":id" => $messageId, ":oldId" => $id));

            $sql = "UPDATE `messages_message` SET `is_history` = 1  WHERE id=:id";
            $sthMessages = $this->dbConnection->prepare($sql);
            $sthMessages->execute(array(":id" => $id));

            $this->dbConnection->commit();

            if (!is_null($loginUserLog))
                $this->Insert($loginUserLog, array("ID"), "login_user_logs");

            $this->UpdateStatus($id, 0);

            return $messageId;
        } catch (\Exception $e) {

            $this->dbConnection->rollBack();
            return false;
        }
    }

    function GetAllBroadcastingCalendarData($from, $to)
    {
        $fromObject = new \DateTime($from);
        $toObject = new \DateTime($to);
        $bindParams = array();

        $sql = "SELECT `Date`,TeaserID AS message_id, TYPE,CASE
               WHEN Teasers.stamp = '1000-01-01 00:00:00'
               THEN 'Default'
               WHEN Teasers.is_termless = 1
               THEN 'Termless'
               WHEN Teasers.`is_high_priority` IS NOT NULL
               THEN 'Priority'
               ELSE 'Other'
               END AS TeaserType,
               CASE
               WHEN stamp = '1000-01-01 00:00:00'
                 THEN 1
                 WHEN is_termless = 1
                 THEN 2
                 WHEN `is_high_priority` IS NOT NULL
                 THEN 3
                 ELSE 4
               END AS TeaserOrder
               FROM `calendar` calendarOut
               LEFT JOIN `teaser_activation_log` teaserActivationOut ON calendarOut.`Date` >= DATE(teaserActivationOut.`Timestamp`)
               AND
               teaserActivationOut.`Timestamp`=
               (
		SELECT MAX(`Timestamp`) FROM teaser_activation_log WHERE DATE(`Timestamp`)<=calendarOut.`Date`
		AND teaser_activation_log.`TeaserID`=teaserActivationOut.`TeaserID`
               )
               LEFT JOIN `messages_message` Teasers ON Teasers.`id` = TeaserID
            WHERE (
            (calendarOut.Date BETWEEN (SELECT `start` FROM messages_periods WHERE Teasers.id =message_id LIMIT 1) AND
                            (SELECT `end` FROM messages_periods WHERE Teasers.id =message_id LIMIT 1) AND (Teasers.stamp = '1000-01-01 00:00:00' OR Teasers.is_termless = 1))
            OR (calendarOut.Date IN (SELECT `start` FROM messages_periods WHERE Teasers.id =message_id ) AND  (Teasers.stamp != '1000-01-01 00:00:00' OR Teasers.is_termless != 1))
              )
                AND calendarOut.`Date` >= :dateFrom AND calendarOut.`Date` <= :dateTo
                AND (teaserActivationOut.`Type`=1 OR teaserActivationOut.`Type` IS NULL)
                AND Teasers.is_history=0 AND Teasers.`is_perm_deleted`=0
               GROUP BY calendarOut.`Date`,teaserActivationOut.`TeaserID`";

        $bindParams[":dateFrom"] = $fromObject->format("Y-m-d");
        $bindParams[":dateTo"] = $toObject->format("Y-m-d");

        $sqlQuery = $this->dbConnection->prepare($sql);
        $sqlQuery->execute($bindParams);
        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }

    function GetBroadcastingCalendarDetails(AjaxGrid $ajaxGrid, $date)
    {
        $condition = "WHERE 1 = 1 AND calendarOut.Date='$date'";

        $criteriaScript = $this->GetCriteriaScript();

        $sql = <<<SQL

        SELECT `Date`,TeaserID AS message_id, TYPE,$criteriaScript,CASE WHEN stamp='1000-01-01 00:00:00' THEN 'Default'
                         WHEN is_termless=1 THEN 'Termless'
                         WHEN `is_high_priority` IS NOT NULL THEN 'Priority'
                         ELSE 'Other'
        END  AS TeaserTypeText,
        CASE WHEN stamp='1000-01-01 00:00:00' THEN '<span class="teaser-icon default-teaser-icon"></span>'
                         WHEN is_termless=1 THEN '<span class="teaser-icon termless-teaser-icon"></span>'
                         WHEN `is_high_priority` IS NOT NULL THEN '<span class="teaser-icon priority-teaser-icon"></span>'
                         ELSE '<span class="teaser-icon other-teaser-icon"></span>'
        END  AS TeaserType,messageOut.id,messageOut.text,messageOut.stamp,messageOut.is_active,
               CASE
               WHEN stamp = '1000-01-01 00:00:00'
                 THEN 1
                 WHEN is_termless = 1
                 THEN 2
                 WHEN `is_high_priority` IS NOT NULL
                 THEN 3
                 ELSE 4
               END AS TeaserOrder
               FROM `calendar` calendarOut
               LEFT JOIN `teaser_activation_log` teaserActivationOut   ON calendarOut.`Date` >= DATE(teaserActivationOut.`Timestamp`)
               AND
               teaserActivationOut.`Timestamp`=
               (
               SELECT MAX(`Timestamp`) FROM teaser_activation_log WHERE DATE(`Timestamp`)<=calendarOut.`Date`
               AND teaser_activation_log.`TeaserID`=teaserActivationOut.`TeaserID`
               )
               LEFT JOIN `messages_message` messageOut ON messageOut.`id` = TeaserID
               $condition AND (calendarOut.Date BETWEEN (SELECT MIN(DATE(`start`)) FROM messages_periods WHERE messageOut.id =message_id) AND
               (SELECT MAX(DATE(`end`)) FROM messages_periods WHERE messageOut.id =message_id)
                OR (SELECT COUNT(*) FROM messages_periods WHERE DATE(`start`)<>calendarOut.Date)=0)


                AND (teaserActivationOut.`Type`=1 OR teaserActivationOut.`Type` IS NULL)
AND messageOut.is_history=0 AND messageOut.`is_perm_deleted`=0

               GROUP BY calendarOut.`Date`,teaserActivationOut.`TeaserID` ORDER BY

SQL;


        if ($ajaxGrid->advanceSorting == null) {
            $sql .= " $ajaxGrid->sortExpression $ajaxGrid->sortOrder ";
        } else {
            $sql .= "{$ajaxGrid->advanceSorting}";
        }

        $sql .= " LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM (SELECT `Date`,TeaserID AS message_id, TYPE,$criteriaScript,CASE
               WHEN messageOut.stamp = '1000-01-01 00:00:00'
               THEN 'Default'
               WHEN messageOut.is_termless = 1
               THEN 'Termless'
               WHEN messageOut.`is_high_priority` IS NOT NULL
               THEN 'Priority'
               ELSE 'Other'
               END AS TeaserType,
               CASE
               WHEN stamp = '1000-01-01 00:00:00'
                 THEN 1
                 WHEN is_termless = 1
                 THEN 2
                 WHEN `is_high_priority` IS NOT NULL
                 THEN 3
                 ELSE 4
               END AS TeaserOrder
               FROM `calendar` calendarOut
               LEFT JOIN `teaser_activation_log` teaserActivationOut   ON calendarOut.`Date` >= DATE(teaserActivationOut.`Timestamp`)
               AND
               teaserActivationOut.`Timestamp`=
               (
               SELECT MAX(`Timestamp`) FROM teaser_activation_log WHERE DATE(`Timestamp`)<=calendarOut.`Date`
               AND teaser_activation_log.`TeaserID`=teaserActivationOut.`TeaserID`
               )
               LEFT JOIN `messages_message` messageOut ON messageOut.`id` = TeaserID
               $condition AND (calendarOut.Date BETWEEN (SELECT MIN(DATE(`start`)) FROM messages_periods WHERE messageOut.id =message_id) AND
               (SELECT MAX(DATE(`end`)) FROM messages_periods WHERE messageOut.id =message_id)
                OR (SELECT COUNT(*) FROM messages_periods WHERE DATE(`start`)<>calendarOut.Date)=0)


                AND (teaserActivationOut.`Type`=1 OR teaserActivationOut.`Type` IS NULL)
AND messageOut.is_history=0 AND messageOut.`is_perm_deleted`=0

               GROUP BY calendarOut.`Date`,teaserActivationOut.`TeaserID`)Z ");

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }

    function GetPriority()
    {
        $sqlQuery = $this->dbConnection->query("SELECT DISTINCT is_high_priority FROM messages_message WHERE is_high_priority IS NOT NULL AND is_deleted != 1 AND is_history !=1 AND is_perm_deleted != 1 ORDER BY is_high_priority");

        return $sqlQuery->fetchAll(\PDO::FETCH_COLUMN);
    }

    function GetTeaserLanguageExceptDefault($teaserId)
    {
        $sql = "SELECT criterion_id FROM `messages_criterion` WHERE `criterion_type_id`=10 AND `message_id`=:TeaserID";

        $sqlQuery = $this->dbConnection->prepare($sql);

        $sqlQuery->bindValue(":TeaserID", $teaserId);

        $sqlQuery->execute();

        $language = $sqlQuery->fetchColumn();

        return $language;
    }

    function GetTeaserLanguage($filterTeaserID = null)
    {
        $filterTeaser = '';

        if ($filterTeaserID != '') {

            $filterTeaser = " AND id<>$filterTeaserID ";
        }
        $sql = "SELECT lang, COUNT(id) AS LangCount, id FROM messages_message WHERE stamp='1000-01-01 00:00:00' AND lang AND lang!=0  IS NOT NULL

              AND is_deleted='0' AND is_perm_deleted = '0' AND is_history='0' $filterTeaser

              GROUP BY lang";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = new \stdClass();

        foreach ($sqlQuery->fetchAll(\PDO::FETCH_OBJ) as $row) {
            $index = ($row->lang);
            $data->{$index} = array("count" => $row->LangCount, "id" => $row->id);
        }

        return $data;
    }

    public function GetAllServices()
    {
        $sql = "SELECT id, service_name FROM service_options ORDER BY service_name ASC";

        $statement = $this->GetDbConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function GetAllChilds($teaserID)
    {
        $array = array($teaserID);
        $flag = true;

        while ($flag) {
            $sql = "SELECT reference_id FROM messages_message WHERE id = :id";
            $statement = $this->GetDbConnection()->prepare($sql);
            $statement->execute(
                array(
                    ":id" => $array[count($array) - 1]
                )
            );

            $reference_id = $statement->fetchColumn();
            if (is_null($reference_id))
                $flag = false;
            else
                $array[] = $reference_id;
        }

        return $array;
    }

    public function CheckNoOfBalanceCriterion()
    {
        $sql = "SELECT message_id FROM `messages_criterion` WHERE `criterion_type_id`=2 GROUP BY message_id
                UNION ALL 
                SELECT id as message_id FROM messages_message WHERE stamp = '1000-01-01 00:00:00' AND is_history = 0 AND is_deleted = 0 AND is_perm_deleted = 0
                ";

        $sqlQuery = $this->dbConnection->prepare($sql);

        $sqlQuery->execute();

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);


    }

}