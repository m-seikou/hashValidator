<?php

namespace mihoshi\hashValidator\loaders;

use mihoshi\hashValidator\interfaces\loaderInterface;

final class hashLoader implements loaderInterface
{
    /**
     * @param array $array
     * @return array
     */
    public function load($array): array
    {
        return $array;
    }

}
