<?php

namespace Infrastructure;

class PHPFormToken {

    public function SetFormToken($tokenName = ""){
        if($tokenName != "" && !empty($tokenName)) {
            $unique = $this->CreateGuid();
            $token = md5($unique);
            $_SESSION[$tokenName] = $token;
        }
    }

    public function GetFormToken($tokenName){
        return isset($_SESSION[$tokenName])?$_SESSION[$tokenName]:null;
    }

    public function UnsetFormToken($tokenName){
        unset($_SESSION[$tokenName]);
    }

    private function CreateGuid($namespace = '') {
        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= isset($_SERVER['REQUEST_TIME'])? $_SERVER['REQUEST_TIME'] : "";
        $data .= isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT'] : "";
        $data .= isset($_SERVER['LOCAL_ADDR'])? $_SERVER['LOCAL_ADDR'] : "";
        $data .= isset($_SERVER['LOCAL_PORT'])? $_SERVER['LOCAL_PORT'] : "";
        $data .= isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : "";
        $data .= isset($_SERVER['REMOTE_PORT'])? $_SERVER['REMOTE_PORT'] : "";
        $hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = '{' .
        substr($hash,  0,  8) .
        '-' .
        substr($hash,  8,  4) .
        '-' .
        substr($hash, 12,  4) .
        '-' .
        substr($hash, 16,  4) .
        '-' .
        substr($hash, 20, 12) .
        '}';
        return $guid;
    }

}