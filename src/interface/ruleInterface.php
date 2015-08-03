<?php

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/rule/ruleException.php';

interface ruleInterface
{
    const ERR_INVALID_VALUE = 1;

    public function __construct($rule);

    public function check($value);

    public function isOptional();

    public function dump();
}