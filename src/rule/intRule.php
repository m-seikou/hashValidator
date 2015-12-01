<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;
require_once __DIR__ . DIRECTORY_SEPARATOR . 'abstractRule.php';

class intRule extends abstractRule
{
    private $min = -PHP_INT_MAX;
    private $max = PHP_INT_MAX;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (isset($rule['min'])) {
            $this->min = $rule['min'];
        }
        if (isset($rule['max'])) {
            $this->max = $rule['max'];
        }
    }

    public function check($value)
    {
        if (!is_numeric($value)) {
            throw new ruleException('invalid int value:' . var_export($value, true));
        }
        $value = (int)$value;
        if ($value < $this->min) {
            throw new ruleException('input:' . $value . ' less than ' . $this->min);
        }
        if ($value > $this->max) {
            throw new ruleException('input:' . $value . ' grater than ' . $this->max);
        }
        return $value;
    }

    public function dump()
    {
        return array_merge(parent::dump(), [
            'type' => 'int',
            'min' => $this->min,
            'max' => $this->max,
        ]);
    }


}