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
use mihoshi\hashValidator\interfaces\ruleInterface;

final class listRule extends abstractRule
{
    /** @var  ruleInterface */
    private $rule;
    private $min;
    private $max;
    private $unique;

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
        $this->unique = (bool)($rule['unique'] ?? false);
        try {
            $this->rule = ruleFactory::getInstance($rule['rule']);
        } catch (invalidRuleException $e) {
            throw new invalidRuleException('[list]' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function check($value)
    {
        $return = [];
        if (!\is_array($value)) {
            throw new invalidDataException('invalid list value:' . var_export($value, true) . ' not array', 0, null,
                $this->message);
        }
        if ($this->min !== null && \count($value) < $this->min) {
            throw new invalidDataException('fewer element :' . \count($value), 0, null, $this->message);
        }
        if ($this->max !== null && \count($value) > $this->max) {
            throw new invalidDataException('more element :' . \count($value), 0, null, $this->message);
        }
        foreach ($value as $key => $element) {
            try {
                $return[$key] = $this->rule->check($element);
            } catch (invalidDataException $e) {
                throw new invalidDataException('[' . $key . ']' . $e->getMessage(), $e->getCode(), $e, $this->message);
            }
        }
        if ($this->unique) {
            $keys = array_keys($return);
            while (null !== $targetKey = array_shift($keys)) {
                foreach ($keys as $key) {
                    if ($return[$key] === $return[$targetKey]) {
                        throw new invalidDataException(sprintf('duplicate entry %s key=>[%s, %s]', $return[$key], $key, $targetKey));
                    }
                }
            }
        }
        return $return;
    }

    public function dump(): array
    {
        $return = array_merge(parent::dump(), [
            'rule' => $this->rule->dump(),
        ]);

        if ($this->min !== null) {
            $return['min'] = $this->min;
        }
        if ($this->max !== null) {
            $return['max'] = $this->max;
        }
        return $return;
    }


}
