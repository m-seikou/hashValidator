<?php

namespace mihoshi\hashValidator;
include_once dirname(__DIR__) . '/interface/loaderInterface.php';
include_once 'loaderException.php';

class yamlLoader implements loaderInterface
{
    public function load($fileName)
    {
        try {
            if (!file_exists($fileName)) {
                throw new loaderException('file not found:' . $fileName, loaderException::ERR_FILE_NOT_READ);
            }
            if (NULL === $return = yaml_parse_file($fileName)) {
                throw new loaderException('file not yaml:' . $fileName, loaderException::ERR_FILE_NOT_READ);
            }
        } catch (loaderException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new loaderException($e->getMessage(), loaderException::ERR_FILE_NOT_READ, $e);
        }
        return $return;


    }

}