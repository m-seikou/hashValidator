<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/interface/ruleInterface.php';

class stringRule implements ruleInterface
{
    private $min = NULL;
    private $max = NULL;
    private $preg = NULL;
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
        if (isset($rule['preg'])) {
            $this->preg = $rule['preg'];
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
        if (!is_scalar($value)) {
            throw new ruleException('invalid string value:' . var_export($value, true));
        }
        $value = (string)$value;
        $len = strlen($value);
        if (isset($this->min) && $len < $this->min) {
            throw new ruleException('input length:' . $len . ' less than ' . $this->min);
        }
        if (isset($this->max) && $len > $this->max) {
            throw new ruleException('input length:' . $len . ' grater than ' . $this->max);
        }
        if (isset($this->preg) && !preg_match($this->preg, $value)) {
            throw new ruleException('input:' . $value . ' not match ' . $this->preg);
        }
        return $value;
    }

    public function dump()
    {
        return [
            'type' => 'string',
            'min' => $this->min,
            'max' => $this->max,
            'preg' => $this->preg,
            'comment' => $this->comment,
            'optional' => $this->optional
        ];
    }


}