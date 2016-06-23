<?php

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . '/interface/loaderInterface.php';

class hashLoader implements loaderInterface
{
    public function load($array)
    {
        return $array;
    }

}