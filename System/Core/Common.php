<?php

function GetClientIp()
{
    $url = "http://ipinfo.io/json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $data = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($data);
    if (is_object($data) && isset($data->ip)) {
        return trim($data->ip);
    } else {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}

function Redirect($uri, $httpResponseCode = 302)
{
    if (!preg_match('#^https?://#i', $uri)) {
        $uri = BASE_URL . $uri;
        header("Location: " . $uri, TRUE, $httpResponseCode);
        die();
    }
}

function GetUri($uri)
{
    if (!preg_match('#^https?://#i', $uri)) {
        $uri = BASE_URL . $uri;
        return $uri;
    }
}

function replaceString($string, $replaceArray)
{
    foreach ($replaceArray as $k => $v) {
        if (is_array($v)) {
            $v = implode(",", $v);
        }
        $string = str_replace($k, $v, $string);
    }
    return $string;
}

function dd($var1 = "here", $line = null, $file = null)
{
    if (is_null($line) and is_null($file)) {
        var_dump($var1);

    } else {
        var_dump($var1);

        echo "at line : $line in file $file";
    }
    exit;
}

function generateRandomAlphaNumericString($length = 7)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function func_mysql_escape_string($string)
{
    $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
    $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
    return str_replace($search, $replace, $string);
}

function returnVarKey($className, $string = null)
{
    $class = new ReflectionClass(new $className);
    $varArray = $class->getStaticProperties();
    if ($string == null)
        return array_keys($varArray);

    else {
        $key = array_search($string, $varArray);
        return (isset($key)) ? $key : $string;
    }
}

if (get_magic_quotes_gpc()) {
    function stripslashes_gpc(&$value)
    {
        $value = stripslashes($value);
    }

    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

function transliterate($textcyr = null, $textlat = null)
{
/*    $cyr = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а',
        'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');

    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'Yo', 'Zh', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sh', '', 'Y',
        '', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sh',
        '', 'y', '', 'e', 'yu', 'ya');*/

    $cyr = array(
        'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я', 'ы',
        'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я', 'Ы');


    $lat = array(
        'zh', 'ch', 'shh', 'sh', 'ju', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y\'', '\'', 'q', 'y',
        'Zh', 'Ch', 'Shh', 'Sh', 'Ju', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y\'', '\'', 'Q', 'Y');


    if ($textcyr) return str_replace($cyr, $lat, $textcyr);
    else if ($textlat) return str_replace($lat, $cyr, $textlat);
    else return null;
}

function ProcessUploadedCsvFile($file, $destination){

    $flag = move_uploaded_file($file, $destination);
    if($flag && mb_check_encoding(file_get_contents($destination),'CP1251')){
        $path = "python converter.py $destination";
        $command = escapeshellcmd($path);
        $output = shell_exec($command);
        if(trim($output) == "Conversion Successfull"){
            return true;
        }
    }else{
        return true;
    }
    return false;
}

function GetCurrentDateTime()
{
    $dbCon = new \Application\Config\ConnectionHelper();

    $sqlQuery = $dbCon->dbConnect()->query("SELECT CURRENT_TIMESTAMP");

    $datetime = $sqlQuery->fetchColumn();

    return $datetime;
}

function GetCurrentLanguage(){
    return $_COOKIE[\Infrastructure\CookieVariable::$BalancePlusLanguage];
}

function date_($date){
    $date = DateTime::createFromFormat("m/d/Y", $date);
    if(is_object($date))
        return $date->format("Y-m-d");
    else {
        if(function_exists("date")){
            return date("Y-m-d", strtotime($date));
        }else{
            return (new DateTime($date.'T00:00:00.00'))->format('Y-m-d');
        }
    }
}