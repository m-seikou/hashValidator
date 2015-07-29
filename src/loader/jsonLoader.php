<?php

namespace mihoshi\hashValidator;
include_once dirname(__DIR__) . '/interface/loaderInterface.php';
include_once 'loaderException.php';

class jsonLoader implements loaderInterface
{
    /**
     * @param string $fileName
     * @return mixed
     * @throws loaderException
     */
    public function load($fileName)
    {
        try {
            if (!file_exists($fileName)) {
                throw new loaderException('file not found:' . $fileName, loaderException::ERR_FILE_NOT_READ);
            }
            if (empty($file = file_get_contents($fileName))) {
                throw new loaderException('file not read:' . $fileName, loaderException::ERR_FILE_NOT_READ);
            }
            if (NULL === $return = json_decode($file, true)) {
                throw new loaderException('file not json:' . $fileName, loaderException::ERR_FILE_NOT_READ);
            }
        } catch (loaderException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new loaderException($e->getMessage(), loaderException::ERR_FILE_NOT_READ, $e);
        }
        return $return;
    }

}