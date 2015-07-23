<?php

namespace mihoshi\hashValidator;

interface loaderInterface
{
    /**
     * @param string $file
     * @return array hash validator rule
     */
    public function load($file);
}