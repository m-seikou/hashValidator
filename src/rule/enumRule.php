<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/interface/ruleInterface.php';

class enumRule implements ruleInterface
{
    private $value = [];
    private $comment = '';
    private $optional = false;

    public function __construct($rule)
    {
        if (!is_array($rule['value'])) {
            throw new ruleException();
        }
        if (count($rule['value']) == 0) {
            throw new ruleException();
        }
        $this->value = $rule['value'];
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
            throw new ruleException('invalid enum value:' . var_export($value, true));
        }
        if (!in_array($value, $this->value)) {
            throw new ruleException('invalid enum value:' . $value . ' in [' . implode(',', $this->value) . ']');
        }
        return $this->value[array_search($value, $this->value)];
    }

    public function dump()
    {
        return [
            'type' => 'enum',
            'value' => $this->value,
            'comment' => $this->comment,
            'optional' => $this->optional
        ];
    }

    public function toText($indentStr, $indentNum)
    {
        $indent = str_repeat($indentStr, $indentNum);
        $return = '';
        $return .= $indent . 'type:enum [' . implode(',', $this->value) . ']' . $this->comment;
        return $return;
    }

}