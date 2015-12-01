<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;
require_once __DIR__ . DIRECTORY_SEPARATOR . 'abstractRule.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'ruleFactory.php';

class hashRule extends abstractRule
{
    /** @var  ruleInterface[] */
    private $rule;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (!isset($rule['key']) && is_array($rule['key'])) {
            throw new ruleException();
        }
        foreach ($rule['key'] as $key => $rule) {
            $this->rule[$key] = ruleFactory::getInstance($rule);
        }
    }

    public function check($value)
    {
        $return = [];
        foreach ($this->rule as $key => $rule) {
            if (false === array_key_exists($key, $value)) {
                if ($rule->isOptional()) {
                    continue;
                } else {
                    throw new ruleException('undefined key:' . $key);
                }
            }

            try {
                $return[$key] = $rule->check($value[$key]);
            } catch (\Exception $e) {
                throw new ruleException('[' . $key . ']' . $e->getMessage(), $e->getCode(), $e);
            }
        }
        return $return;
    }

    public function dump()
    {
        $return = array_merge(parent::dump(), [
            'type' => 'hash',
            'key' => [],
        ]);
        foreach ($this->rule as $key => $rule) {
            $return['key'][$key] = $rule->dump();
        }
        return $return;
    }


}