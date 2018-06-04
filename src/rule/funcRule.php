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

final class funcRule extends abstractRule
{
	private $function = null;

	public function __construct($rule)
	{
		parent::__construct($rule);
		if (isset($rule['class'])) {
			if (!class_exists($rule['class'])) {
				throw new invalidRuleException('class:' . $rule['class'] . ' not exist');
			}
			if (!isset($rule['method'])) {
				throw new invalidRuleException('method is not undefined');
			}
			if (!method_exists($rule['class'], $rule['method'])) {
				throw new invalidRuleException('method:' . $rule['method'] . ' not exist in ' . $rule['class']);
			}
			$this->function = [$rule['class'], $rule['method']];
		} elseif (isset($rule['function'])) {
			if (!function_exists($rule['function'])) {
				throw new invalidRuleException('method:' . $rule['method'] . ' not exist in ' . $rule['class']);
			}
			$this->function = $rule['function'];
		} else {
			throw new invalidRuleException('func rule require "class" or "function" property');
		}
		if (isset($rule['comment'])) {
			$this->comment = $rule['comment'];
		}
		if (isset($rule['optional'])) {
			$this->optional = $rule['optional'];
		}
	}

	public function check($value)
	{
		try {
			$value = call_user_func($this->function, $value);
		} catch (\Exception $e) {
			throw new invalidDataException($e->getMessage(), $e->getCode(), $e);
		}
		return $value;
	}

	public function dump()
	{
		if (is_array($this->function)) {
			return array_merge(parent::dump(), [
				'class' => $this->function[0],
				'method' => $this->function[1],
			]);
		} else {
			return array_merge(parent::dump(), [
				'function' => $this->function,
			]);
		}
	}


}
