<?php

namespace System\MVC;

abstract class ModelAbstract
{
    public function MapParameters($params, $nullEmpty = false)
    {
        foreach ($params as $key => $val) {
            if (property_exists($this, $key)) {
                if ($nullEmpty && $val == '') {
                    $this->$key = NULL;
                } else {
                    $this->$key = isset($val) && trim($val)!="" ?trim($val):NULL;
                }
            }
        }
    }
}