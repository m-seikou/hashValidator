<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/interface/ruleInterface.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'ruleFactory.php';

class listRule implements ruleInterface
{
    /** @var  ruleInterface */
    private $rule;
    private $comment = '';
    private $optional = false;

    public function __construct($rule)
    {
        if (!is_array($rule['rule'])) {
            throw new ruleException();
        }
        if (isset($rule['comment'])) {
            $this->comment = $rule['comment'];
        }
        if (isset($rule['optional'])) {
            $this->optional = $rule['optional'];
        }
        $this->rule = ruleFactory::getInstance($rule['rule']);
    }

    public function check($value)
    {
        $return = [];
        if (!is_array($value)) {
            throw new ruleException('invalid list value:' . $value . ' not array');
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
        return [
            'type' => 'list',
            'rule' => $this->rule->dump(),
            'comment' => $this->comment,
            'optional' => $this->optional
        ];
    }


}