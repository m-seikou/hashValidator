<?php

namespace mihoshi\hashValidator\interfaces;

interface loaderInterface
{
    /**
     * @param string $file
     * @return array hash validator rule
     */
    public function load($file): array;
}
