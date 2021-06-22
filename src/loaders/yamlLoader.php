<?php

namespace mihoshi\hashValidator\loaders;

use mihoshi\hashValidator\interfaces\loaderInterface;
use mihoshi\hashValidator\exceptions\loaderException;

final class yamlLoader extends abstractFileLoader
{
    /**
     * @param string $file
     * @return array
     * @throws loaderException
     */
    public function load($file): array
    {
        if (!file_exists($file)) {
            throw new loaderException('file not found:' . $file, loaderException::ERR_FILE_NOT_READ);
        }
        try {
            if (!\is_array($return = yaml_parse_file($file))) {
                throw new loaderException('file not yaml:' . $file, loaderException::ERR_FILE_NOT_READ);
            }
        } catch (loaderException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new loaderException($e->getMessage(), loaderException::ERR_FILE_NOT_READ, $e);
        }
        return $this->resolveIncludeFile($return, \dirname($file));
    }
}
