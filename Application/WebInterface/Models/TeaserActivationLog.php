<?php

namespace WebInterface\Models;

use System\MVC\ModelAbstract;

class TeaserActivationLog extends ModelAbstract
{
    public $ID;

    public $TeaserID;

    public $Text;

    public $SmsText;

    public $ActivationText;

    public $TeaserType;

    public $Language;

    public $UpdatedBy;

    public $LimitOfShows;

    public $Priority;

    public $Timestamp;

    public $Date;

    public $Time;

    public $Type;

    public $ServiceID;

    public $ServiceName;

    public $PreviousUpdateOn;

    public $IsLatest;

    public $CreatedDate;

    public $IsLatestGroup;

}