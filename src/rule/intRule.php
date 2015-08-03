<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/interface/ruleInterface.php';

class intRule implements ruleInterface
{
    private $min = -PHP_INT_MAX;
    private $max = PHP_INT_MAX;
    private $comment = '';
    private $optional = false;

    public function __construct($rule)
    {
        if (isset($rule['min'])) {
            $this->min = $rule['min'];
        }
        if (isset($rule['max'])) {
            $this->max = $rule['max'];
        }
        if (isset($rule['comment'])) {
            $this->comment = $rule['comment'];
        }
        if (isset($rule['optional'])) {
            $this->optional = $rule['optional'];
        }
    }

    public function isOptional()
    {
        return $this->optional;
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
        return [
            'type' => 'int',
            'min' => $this->min,
            'max' => $this->max,
            'comment' => $this->comment,
            'optional' => $this->optional
        ];
    }


}