<?php

namespace mihoshi\hashValidator;
include_once '../interface/loaderInterface.php';

class hashLoader implements loaderInterface
{
    public function load($array)
    {
        return $array;
    }

}