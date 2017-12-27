<?php

namespace Admin\Repositories;

use Admin\Models\LoginUser;
use Language\Russian\Russian;
use Shared\Model\AjaxGrid;
use System\Repositories\Repo;

use Language\English\English;

class LoginUserRepository extends Repo
{
    private $table;

    private $MaximumPasswordReuse = 3;

    private $MaximumPasswordReuseException = "Maximum Password Reuse Exception";


    function __construct()
    {
        $this->table = "login_user";



        parent::__construct($this->table, "Admin\\Models\\LoginUser");

    }

    public function Save(LoginUser $user, $loginUserLog)
    {
        $this->dbConnection->beginTransaction();

        try {
            $this->Insert($user, array("ID"));

            if(!is_null($loginUserLog))

                $this->Insert($loginUserLog, array("ID"), "login_user_logs");

            $this->dbConnection->commit();
            return true;

        } catch (\Exception $e) {

            $this->dbConnection->rollBack();

            return false;
        }
    }

    public function Update(LoginUser $user, $changePassword = false, $loginUserLog)
    {
        $this->dbConnection->beginTransaction();

        try {

            if (!$changePassword)
                $this->UpdateTable($user, array("ID", "Password", "DateCreated","LastLogin"));
            else
                $this->UpdateTable($user, array("ID"));

            if(!is_null($loginUserLog))
                $this->Insert($loginUserLog, array("ID"), "login_user_logs");

            $this->dbConnection->commit();

            return true;

        } catch (\Exception $e) {

            $this->dbConnection->rollBack();

            return false;
        }
    }

    function CheckLogin(LoginUser $users)
    {
        $sqlQuery = $this->GetDbConnection()->prepare("SELECT ID,UserType  FROM {$this->table} WHERE Username = :Username AND Password = :Password;");

        $sqlQuery->bindParam(":Username", $users->Username);
        $sqlQuery->bindParam(":Password", $users->Password);

        $sqlQuery->execute();

        if ($sqlQuery->rowCount() != 0) {

            $row = $sqlQuery->fetch();

            $users->MapParameters($row);

            $sqlQuery = $this->GetDbConnection()->prepare("UPDATE {$this->table} SET LastLogin=CURRENT_TIMESTAMP WHERE ID=:ID");
            $sqlQuery->bindParam(':ID', $row['ID']);
            $sqlQuery->execute();


            return $users;
        }
        return false;
    }

    function FindAll(AjaxGrid $ajaxGrid,$language = NULL)
    {
        if($language=="Russian") {
            $language = new Russian();
        }else {
            $language = new English();
        }
        $customerCare = $language->CustomerCare;
        $moderator = $language->Moderator;
        $operator = $language->Operator;
        $administrator = $language->Administrator;

        $sql = "SELECT *, CASE UserType WHEN 1 THEN '$administrator' WHEN 2 THEN '$moderator' WHEN 3 THEN '$operator' WHEN 4 THEN '$customerCare' END AS UserTypeShow
                FROM {$this->table}
                ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";

        $sqlQuery = $this->GetDbConnection()->query($sql);


        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM {$this->table}");
        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }

    function CheckUsernameExists($username, $id)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}  WHERE Username='$username'";

        if ($id != '' && $id > 0 && $id != null) {
            $sql .= " AND ID<>$id";
        }

        return $this->GetDbConnection()->query($sql)->fetchColumn();
    }

    function CheckIfOldPasswordMatches($userId, $oldPassword)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE ID=$userId AND Password='$oldPassword'";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchColumn() > 0 ? true : false;
    }

    public function UpdatePassword($userId, $newPassword)
    {
        try {

            $countSth = $this->dbConnection->prepare("SELECT COUNT(*) as Count FROM `password_history` WHERE UserID = :id AND Password = :newPassword");
            $countSth->execute(array(":id"=>$userId,":newPassword"=>$newPassword));
            $countObj = $countSth->fetch(\PDO::FETCH_OBJ);

            if($countObj->Count < $this->MaximumPasswordReuse){

                $historySth = $this->dbConnection->prepare("INSERT INTO `password_history` (UserID, Password, DateTime) SELECT ID, Password, CURRENT_TIMESTAMP FROM {$this->table} WHERE ID = :id");
                $historySth->execute(array(":id"=>$userId));

                $sth = $this->dbConnection->prepare("UPDATE {$this->table} SET Password=:password, PasswordUpdatedDate = CURDATE() WHERE ID=:id");
                $sth->execute(array(":password"=>$newPassword, ":id"=>$userId));
                return 1;
            }else{
                $error = $this->MaximumPasswordReuseException;
                throw new \Exception($error);
            }

        } catch (\Exception $e) {
            if($e->getMessage() == $this->MaximumPasswordReuseException)
                return -1;

            return 0;
        }
    }

    public function DeleteUser($userId, $loginUserLog){

        $this->dbConnection->beginTransaction();

        try{

            $this->Delete($userId);

            if(!is_null($loginUserLog))

                $this->Insert($loginUserLog, array("ID"), "login_user_logs");

            $this->dbConnection->commit();

            return true;

        }catch (\Exception $e){

            $this->dbConnection->rollBack();

            return false;
        }

    }

    public function CheckPasswordExpiry($loginUserID){

        $sql = "SELECT COUNT(*) as Expired FROM login_user WHERE `ID` = :userID AND CURDATE() > DATE_ADD(PasswordUpdatedDate, INTERVAL 90 DAY)";

        $sqlQuery = $this->dbConnection->prepare($sql);

        $sqlQuery->execute(array(":userID"=>$loginUserID));

        $expired = $sqlQuery->fetch(\PDO::FETCH_OBJ);

        return $expired;
    }

    public function UserLoggedOutLog($loginUserLog){
        try{

            $this->Insert($loginUserLog, array("ID"), "login_user_logs");

            return true;

        }catch (\Exception $e){

            return false;

        }


    }
}