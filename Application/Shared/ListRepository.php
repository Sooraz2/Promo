<?php

namespace Shared;

use System\Repositories\ListRepo;

/**
 * @method GetAllBlackListGeneral
 * @method GetAllCriterionRegion
 * @method GetAllBlacklist
 * @method GetAllBroadcastSchedule
 * @method GetAllSubscriberGroupList
 * @method GetAllTargetGroupCriteriaDetails
 * @method GetAllSubscriberGroupTargetGroup
 * @method GetAllLanguage
 * @method GetLastRecharge
 *
 * @method WhereTeaserList
 * @method WhereBlacklist
 * @method WhereBroadcastSchedule
 * @method WhereSubscriberGroupList
 * @method WhereTargetGroupCriteriaDetails
 * @method WhereSubscriberGroupTargetGroup
 */
class ListRepository extends ListRepo
{
    public function __call($function, $arguments)
    {
        $test = explode("GetAll", $function);
        if (count($test) == 2 && empty($test[0])) {
            $model = "WebInterface\\Models\\" . $test[1];

            $table = $model::GetTable();

            $this->SetModel($table, $model);

            return $this->GetAll();
        } else {
            $test = explode("Where", $function);
            if (count($test) == 2 && empty($test[0])) {

                $model = "WebInterface\\Models\\" . $test[1];

                $table = $model::GetTable();

                $this->SetModel($table, $model);

                return $this->Where($arguments[0]);
            } else {
                return array();
            }
        }
    }


} 