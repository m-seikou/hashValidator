<?php

namespace mihoshi\hashValidator;
include_once dirname(__DIR__) . '/interface/loaderInterface.php';
include_once 'loaderException.php';

class yamlLoader implements loaderInterface
{
    public function load($file)
    {
        try {
            return yaml_parse_file($file);
        } catch (\Exception $e) {
            throw new loaderException($e->getMessage(), loaderException::ERR_FILE_NOT_READ, $e);
        }
    }

}