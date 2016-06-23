<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/08/03
 * Time: 10:18
 */

namespace mihoshi\hashValidator;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'ruleException.php';

class ruleFactory
{
    public static function getInstance(array $rule)
    {
        try {
            $class = $rule['type'] . 'Rule';
            if (!file_exists(__DIR__ . DIRECTORY_SEPARATOR . $class . '.php')) {
                throw new ruleException('rule not found:' . $rule['type']);
            }
            require_once __DIR__ . DIRECTORY_SEPARATOR . $class . '.php';
            $class = __NAMESPACE__ . '\\' . $class;
            unset($rule['type']);
            return new $class($rule);
        } catch (ruleException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new ruleException($e->getMessage(), $e->getCode(), $e);
        }
    }
}