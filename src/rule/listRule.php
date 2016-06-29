<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'abstractRule.php';

class listRule extends abstractRule
{
    /** @var  ruleInterface */
    private $rule;
    private $min;
    private $max;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (!isset($rule['rule'])) {
            throw new ruleException();
        }
        if (isset($rule['min'])) {
            $this->min = $rule['min'];
        }
        if (isset($rule['max'])) {
            $this->max = $rule['max'];
        }
        $this->rule = ruleFactory::getInstance($rule['rule']);
    }

    public function check($value)
    {
        $return = [];
        if (!is_array($value)) {
            throw new ruleException('invalid list value:' . $value . ' not array');
        }
        if (!is_null($this->min) && count($value) < $this->min) {
            throw new ruleException('fewer element :' . count($value));
        }
        if (!is_null($this->max) && count($value) > $this->max) {
            throw new ruleException('more element :' . count($value));
        }
        foreach ($value as $key => $element) {
            try {
                $return[$key] = $this->rule->check($element);
            } catch (ruleException $e) {
                throw new ruleException('[' . $key . ']' . $e->getMessage(), $e->getCode(), $e);
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
