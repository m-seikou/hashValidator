<?php

namespace mihoshi\hashValidator\loader;

interface loaderInterface
{
    /**
     * @param string|array $file
     * @return array hash validator rule
     */
    public function load($file);
}
