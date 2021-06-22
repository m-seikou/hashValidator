<?php


namespace mihoshi\hashValidator\loaders;


use mihoshi\hashValidator\exceptions\loaderException;
use mihoshi\hashValidator\interfaces\loaderInterface;

abstract class abstractFileLoader implements loaderInterface
{
    /**
     * @param $def
     * @param string $path
     * @return array|string
     * @throws loaderException
     */
    protected function resolveIncludeFile($def, string $path)
    {
        if (!\is_array($def)) {
            return $def;
        }
        foreach ($def as &$d) {
            if (isset($d['include'])) {
                $fileName = realpath($path . DIRECTORY_SEPARATOR . $d['include']);
                unset($d['include']);
                $d = array_merge_recursive($d, $this->load($fileName));
            }
            $d = $this->resolveIncludeFile($d, $path);
        }
        return $def;
    }

}
