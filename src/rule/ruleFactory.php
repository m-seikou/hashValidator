<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/08/03
 * Time: 10:18
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidRuleException;

class ruleFactory
{
	public static function getInstance(array $rule, array $directories = [])
	{
		$directories[] = __DIR__;
		try {
			$class = $rule['type'] . 'Rule';
			$exist = false;
			foreach ($directories as $dir) {
				if (file_exists($dir . DIRECTORY_SEPARATOR . $class . '.php')) {
					require_once $dir . DIRECTORY_SEPARATOR . $class . '.php';
					$exist = true;
					break;
				}
			}
			if (!$exist) {
				throw new invalidRuleException('rule not found:' . $rule['type']);
			}
			$class = __NAMESPACE__ . '\\' . $class;
			unset($rule['type']);
			return new $class($rule);
		} catch (invalidRuleException $e) {
			throw $e;
		} catch (\Exception $e) {
			throw new invalidRuleException($e->getMessage(), $e->getCode(), $e);
		}
	}
}
