<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/08/03
 * Time: 10:18
 */

namespace mihoshi\hashValidator;


class ruleFactory
{
    public static function getInstance(array $rule)
    {
        $class = $rule['rule']['type'] . 'Rule';
        unset($rule['rule']['type']);
        return new $class($rule['rule']);
    }
}