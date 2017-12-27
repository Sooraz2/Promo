<?php

namespace Libraries\Validation;

class Validation {

    private $ToValidate;
    private $DefaultValidations = array(
        "lowercase",
        "uppercase",
        "required",
        "lessThan",
        "greaterThan",
        "numeric",
        "wholeNumber",
        "float",
        "groupRequired",
        "conditionRequired",
        "maxLength",
        "minLength"
    );
    private $Validations;
    private $ValidationsList;
    private $Valid;

    private $MessagesList = array(
        "lowercase" => "{0} is not Lowercase",
        "uppercase" => "{0} is not Uppercase",
        "required" => "Please enter the {0}",
        "lessThan" => "{0} is not less than {1}",
        "greaterThan" => "{0} is not greater than {1}",
        "numeric" => "Entered {0} is not a numeric value",
        "wholeNumber" => "Entered {0} is not a whole number",
        "float" => "Entered {0} is not float value",
        "groupRequired" => "Please Enter at least one of these fields : {0}",
        "conditionRequired" => "Please enter the {0}",
        "maxLength" => "{0} cannot have more than {1} characters",
        "minLength" => "{0} must have at least {1} characters"
    );

    private $Messages;

    public function __construct(array $array = array())
    {
        $this->Validations = array();
        $this->ValidationsList = array();
        $this->Valid = true;
        $this->ToValidate = $array;
        $this->Messages = array();
    }

    public function SetValidationArray($validationArray){
        $this->ToValidate = $validationArray;
        return $this;
    }

    public function ClearValidator(){
        $this->ToValidate = array();
    }

    public function AddCustomValidation($validationName, $validation, $message){
        $this->ValidationsList[$validationName]["callable"] = $validation;
        $this->ValidationsList[$validationName]["message"] = $message;

        return $this;
    }

    public function ValidationRule($field, $validations)
    {
        $this->Validations[$field] = $validations;
        return $this;
    }

    public function PrintValidator(){
        var_dump($this->Validations);
    }

    public function Validate(){
        $flags = array();

        foreach($this->Validations as $field => $validation){

            foreach($validation as $key => $value){
                if(!is_array($value)){
                    $flags[] = $this->CheckValid($field, $value);
                }else{
                    $flags[] = $this->CheckValidArray($field, $value);
                }
            }
        }

        return (object) array("status" => $this->CheckValidationState($flags), "messages" => $this->Messages);
    }

    private function CheckValidationState($flags){

        foreach($flags as $bool){
            if(!$bool){
                return false;
            }
        }

        return true;
    }

    private function CheckValidArray($field, $validation){
        $flag = false;
        $value = $this->ToValidate[$field];
        $secondMessageArgument = null;
        $isCustomValidation = false;

        if(in_array($validation[0], $this->DefaultValidations)) {
            if ($validation[0] == "conditionRequired"){
                if(isset($this->ToValidate[$validation[1]])) {
                    $referenced = $this->ToValidate[$validation[1]];
                    if ($referenced != "" || $referenced != null) {
                        if ($value != "" || $value != null)
                            $flag = true;
                        else
                            $flag = false;
                    } else {
                        $flag = true;
                    }
                }
            }elseif($validation[0] == "groupRequired") {
                $temp_flag = false;
                foreach ($validation[1] as $validateField) {
                    if(isset($this->ToValidate[$validateField])) {
                        $checkValue = $this->ToValidate[$validateField];
                        if ($checkValue != "" || $checkValue != null) {
                            $temp_flag = true;
                            break;
                        } else
                            $temp_flag = false;
                    }
                }

                $flag = $temp_flag;
            }elseif($validation[0] == "lessThan") {
                if ($value < $validation[1])
                    $flag = true;
                else {
                    $flag = false;
                    $secondMessageArgument = $validation[1];
                }
            }elseif($validation[0] == "greaterThan") {
                if ($value > $validation[1])
                    $flag = true;
                else {
                    $flag = false;
                    $secondMessageArgument = $validation[1];
                }
            }elseif($validation[0] == "maxLength") {
                if (strlen($value) <= $validation[1])
                    $flag = true;
                else {
                    $flag = false;
                    $secondMessageArgument = $validation[1];
                }
            }elseif($validation[0] == "minLength") {
                if (strlen($value) >= $validation[1])
                    $flag = true;
                else {
                    $flag = false;
                    $secondMessageArgument = $validation[1];
                }
            }
        }else{
            $isCustomValidation = true;
            $temp_validation = $this->ValidationsList[$validation[0]];
            $parameter = array($value);
            if(isset($validation[1]))
                $parameter = array_merge_recursive($parameter, $validation[1]);

            foreach($parameter as $key => $value){
                if(in_array($value, array_keys($this->ToValidate))){
                    $parameter[$key] = $this->ToValidate[$value];
                }
            }

            $flag = call_user_func_array($temp_validation["callable"], $parameter);
            if(gettype($flag) === "boolean") {
                if (!$flag) {
                    $this->Messages[] = $temp_validation["message"];
                }
            }else
                throw new Exception("Return type must be boolean for method \"{$temp_validation["callable"][1]}\" in class \"".get_class($temp_validation["callable"][0])."\"");
        }

        if(!$isCustomValidation) {
            if ($validation[0] == "groupRequired") {
                if (!$flag)
                    $this->SetMessage($validation[0], array(implode(",", $validation[1])));
            } else {
                if (!($flag) && is_null($secondMessageArgument))
                    $this->SetMessage($validation[0], array($field));
                else if (!($flag) && !is_null($secondMessageArgument))
                    $this->SetMessage($validation[0], array($field, $secondMessageArgument));
            }
        }

        return $flag;
    }

    private function CheckValid($field, $validation){
        $flag = false;
        $value = $this->ToValidate[$field];

        if($validation == "required"):
            if($value != "" || $value != null)
                $flag = true;
            else
                $flag = false;
        elseif($validation == "lowercase"):
            if(strtolower($value) === $value)
                $flag = true;
            else
                $flag = false;
        elseif($validation == "uppercase"):
            if(strtoupper($value) === $value)
                $flag = true;
            else
                $flag = false;
        elseif($validation == "numeric"):
            if(is_numeric($value))
                $flag = true;
            else
                $flag = false;
        elseif($validation == "wholeNumber"):
            if(floor($value) == $value)
                $flag = true;
            else
                $flag = false;
        elseif($validation == "float") :
            $floatVal = floatval($value);
            if($floatVal && intval($floatVal) != $floatVal)
                $flag = true;
            else
                $flag = false;
        endif;

        if($flag == false)
            $this->SetMessage($validation, array($field));

        return $flag;
    }

    private function SetMessage($validation, $fields = array()){
        $message = $this->MessagesList[$validation];
        for($i = 0; $i < count($fields); $i++){
            if(isset($this->MessagesList[$validation])){
                $message = str_replace('{'.$i.'}', $fields[$i], $message);
            }
        }
        $this->Messages[] = $message;
    }
}