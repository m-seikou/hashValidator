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

final class listRule extends abstractRule
{
    /** @var  ruleInterface */
    private $rule;
    private $min;
    private $max;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (!isset($rule['rule'])) {
            throw new invalidRuleException('invalid rule format');
        }
        if (isset($rule['min'])) {
            $this->min = (int)$rule['min'];
        }
        if (isset($rule['max'])) {
            $this->max = (int)$rule['max'];
        }
        try {
            $this->rule = ruleFactory::getInstance($rule['rule']);
        } catch (invalidRuleException $e) {
            throw new invalidRuleException('[list]' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function check($value)
    {
        $return = [];
        if (!is_array($value)) {
            throw new invalidDataException('invalid list value:' . var_export($value,true) . ' not array', 0, null, $this->message);
        }
        if (!is_null($this->min) && count($value) < $this->min) {
            throw new invalidDataException('fewer element :' . count($value), 0, null, $this->message);
        }
        if (!is_null($this->max) && count($value) > $this->max) {
            throw new invalidDataException('more element :' . count($value), 0, null, $this->message);
        }
        foreach ($value as $key => $element) {
            try {
                $return[$key] = $this->rule->check($element);
            } catch (invalidDataException $e) {
                throw new invalidDataException('[' . $key . ']' . $e->getMessage(), $e->getCode(), $e, $this->message);
            }
        }
        return $return;
    }

    public function dump()
    {
        $return = array_merge(parent::dump(), [
            'rule' => $this->rule->dump(),
        ]);

        if (!is_null($this->min)) {
            $return['min'] = $this->min;
        }
        if (!is_null($this->max)) {
            $return['max'] = $this->max;
        }
        return $return;
    }


}
