<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/15/2015
 * Time: 3:37 PM
 */
class Auth_Filters{

    public $filters = array(
        //controllers or methods that CANNOT be used by auth admin type

        "moderator"=>array(""),
            //controllers or methods that CANNOT be used by auth operator type
        "editor"=>array(  "Countries",),
            //controllers or methods that CAN be used by auth guest or no authorisation type
        "readonly" => array("Login", "NoAccess","CountriesAndOperator","ChangePassword",
            "GeneralInformation",
            "AllocationCriteria",
            "Status",
            "ActivityStream",
            "Reports",
            "MyProfile",
            "LogOut"
            )
        );

    public $intended = "NoAccess@index";
}
