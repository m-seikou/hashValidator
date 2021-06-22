<?php

namespace mihoshi\hashValidator\loaders;

use mihoshi\hashValidator\interfaces\loaderInterface;
use mihoshi\hashValidator\exceptions\loaderException;

final class jsonLoader extends abstractFileLoader
{
    /**
     * @param string $file
     * @return mixed
     * @throws loaderException
     */
    public function load($file): array
    {
        if (!file_exists($file)) {
            throw new loaderException('file not found:' . $file, loaderException::ERR_FILE_NOT_READ);
        }
        if (empty($fileData = file_get_contents($file))) {
            throw new loaderException('file not read:' . $file, loaderException::ERR_FILE_NOT_READ);
        }
        try {
            $return = json_decode($fileData, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new loaderException('file not json:' . $file, loaderException::ERR_FILE_NOT_READ, $e);
        } catch (\Exception $e) {
            throw new loaderException($e->getMessage(), loaderException::ERR_FILE_NOT_READ, $e);
        }
        return $this->resolveIncludeFile($return, \dirname($file));
    }
}
