<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidRuleException;
use mihoshi\hashValidator\exceptions\invalidDataException;

class enumRule extends abstractRule
{
    private $value;
    private $return;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (!is_array($rule['value'])) {
            throw new invalidRuleException('array has not key:value');
        }
        if (count($rule['value']) === 0) {
            throw new invalidRuleException('value is empty');
        }
        $this->value = $rule['value'];
        if (isset($rule['comment'])) {
            $this->comment = $rule['comment'];
        }
        if (isset($rule['optional'])) {
            $this->optional = $rule['optional'];
        }
        $v0 = $rule['value'][0];
        if (is_string($v0)) {
            $this->return = 'string';
        } elseif (is_int($v0)) {
            $this->return = 'int';
        } elseif (is_float($v0)) {
            $this->return = 'float';
        } elseif (is_bool($v0)) {
            $this->return = 'bool';
        }
    }

    public function check($value)
    {
        if (!is_scalar($value)) {
            throw new invalidDataException('invalid enum value:' . var_export($value, true), 0, null, $this->message);
        }
        if (!in_array($value, $this->value, true)) {
            throw new invalidDataException('invalid enum value:' . $value . ' in [' . implode(',', $this->value) . ']',
                0, null, $this->message);
        }
        return $this->value[array_search($value, $this->value, true)];
    }

    public function dump(): array
    {
        return array_merge(parent::dump(), [
            'value' => $this->value,
            'return' => $this->return,
            'type' => 'enum',
        ]);
    }

    public function toText($indentStr, $indentNum): string
    {
        $indent = str_repeat($indentStr, $indentNum);
        return $indent . 'type:enum [' . implode(',', $this->value) . ']' . $this->comment;
    }
}
