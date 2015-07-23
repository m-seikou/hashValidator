<?php

namespace mihoshi\hashValidator;
include_once '../interface/loaderInterface.php';

class jsonLoader implements loaderInterface
{
    public function load($file)
    {
        try {
            return json_decode(file_get_contents($file), true);
        } catch (\Exception $e) {
            throw new hashValidatorException($e->getMessage(), hashValidator::ERR_FILE_NOT_READ, $e);
        }
    }

}