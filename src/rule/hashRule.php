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

class hashRule extends abstractRule
{
    /** @var array ruleInterface[] */
    private array $rule;

    /**
     * hashRule constructor.
     * @param array $rule
     */
    public function __construct($rule)
    {
        parent::__construct($rule);
        if (!isset($rule['key']) || !\is_array($rule['key'])) {
            throw new invalidRuleException('undefined "key" data ');
        }
        foreach ($rule['key'] as $key => $rule) {
            try {
                $this->rule[$key] = ruleFactory::getInstance($rule);
            } catch (invalidRuleException $e) {
                throw new invalidRuleException('[' . $key . ']' . $e->getMessage(), $e->getCode(), $e);
            }
        }
    }

    public function check($value)
    {
        $return = [];
        foreach ($this->rule as $key => $rule) {
            if (false === array_key_exists($key, $value)) {
                if ($rule->isOptional()) {
                    continue;
                }
                if (($default = $rule->getDefault()) !== null) {
                    $return[$key] = $default;
                    continue;
                }
                throw new invalidDataException('undefined key:' . $key, 0, null, $this->message);

            }

            try {
                $return[$key] = $rule->check($value[$key]);
            } catch (\Exception $e) {
                throw new invalidDataException('[' . $key . ']' . $e->getMessage(), $e->getCode(), $e, $this->message);
            }
        }
        return $return;
    }

    public function dump(): array
    {
        $return = array_merge(parent::dump(), [
            'key' => [],
            'type' => 'hash',
        ]);
        foreach ($this->rule as $key => $rule) {
            $return['key'][$key] = $rule->dump();
        }
        return $return;
    }

}
