<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'abstractRule.php';

class stringRule extends abstractRule
{
    private $min = null;
    private $max = null;
    private $preg = null;

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
        return array_merge(parent::dump(), [
            'type' => 'string',
            'min'  => $this->min,
            'max'  => $this->max,
            'preg' => $this->preg,
        ]);
    }


}