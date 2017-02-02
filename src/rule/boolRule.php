<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidDataException;

final class boolRule extends abstractRule
{
    private $null = false;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (isset($rule['arrow_null'])) {
            $this->null = $rule['arrow_null'];
        }
    }

    public function check($value)
    {
        if (is_null($value) && $this->null) {
            return null;
        }
        if (!is_bool($value)) {
            throw new invalidDataException('invalid int value:' . var_export($value, true), 0, null, $this->message);
        }
        $value = (bool)$value;
        return $value;
    }
}
