<?php

namespace mihoshi\hashValidator;
include_once '../interface/loaderInterface.php';

class yamlLoader implements loaderInterface
{
    public function load($file)
    {
        try {
            return yaml_parse_file($file);
        } catch (\Exception $e) {
            throw new hashValidatorException($e->getMessage(), hashValidator::ERR_FILE_NOT_READ, $e);
        }
    }

}