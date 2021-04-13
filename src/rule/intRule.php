<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidDataException;

class intRule extends abstractRule
{
    private $min = -PHP_INT_MAX;
    private $max = PHP_INT_MAX;
    private $null = false;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (isset($rule['min'])) {
            $this->min = $rule['min'];
        }
        if (isset($rule['max'])) {
            $this->max = $rule['max'];
        }
        if (isset($rule['arrow_null'])) {
            $this->null = $rule['arrow_null'];
        }
    }

    public function check($value)
    {
        if ($value === null && $this->null) {
            return null;
        }
        if (!is_numeric($value)) {
            throw new invalidDataException('invalid int value:' . var_export($value, true), 0, null, $this->message);
        }
        $value = (int)$value;
        if ($value < $this->min) {
            throw new invalidDataException('input:' . $value . ' less than ' . $this->min, 0, null, $this->message);
        }
        if ($value > $this->max) {
            throw new invalidDataException('input:' . $value . ' grater than ' . $this->max, 0, null, $this->message);
        }
        return $value;
    }

    public function dump(): array
    {
        return array_merge(parent::dump(), [
            'min' => $this->min,
            'max' => $this->max,
        ]);
    }


}
