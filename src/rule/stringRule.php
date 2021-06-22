<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidDataException;

class stringRule extends abstractRule
{
    private $min;
    private $max;
    private $preg;
    private bool $null = false;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (isset($rule['min'])) {
            $this->min = $rule['min'];
        }
        if (isset($rule['max'])) {
            $this->max = $rule['max'];
        }
        if (isset($rule['preg'])) {
            $this->preg = $rule['preg'];
        }
        if (isset($rule['arrow_null'])) {
            $this->null = $rule['arrow_null'];
        }
    }

    public function check($value): ?string
    {
        if ($value === null && $this->null) {
            return null;
        }
        if (!is_scalar($value)) {
            throw new invalidDataException('invalid string value:' . var_export($value, true), 0, null, $this->message);
        }
        $value = (string)$value;
        $len = strlen($value);
        if ($this->min !== null && $len < $this->min) {
            throw new invalidDataException('input length:' . $len . ' less than ' . $this->min, 0, null,
                $this->message);
        }
        if ($this->max !== null && $len > $this->max) {
            throw new invalidDataException('input length:' . $len . ' grater than ' . $this->max, 0, null,
                $this->message);
        }
        if ($this->preg !== null && !preg_match($this->preg, $value)) {
            throw new invalidDataException('input:' . $value . ' not match ' . $this->preg, 0, null, $this->message);
        }
        return $value;
    }

    public function dump(): array
    {
        return array_merge(parent::dump(), [
            'min' => $this->min,
            'max' => $this->max,
            'preg' => $this->preg,
            'type' => 'string'
        ]);
    }
}
