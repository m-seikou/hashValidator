<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/11/30
 * Time: 11:10
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\interfaces\ruleInterface;

abstract class abstractRule implements ruleInterface
{
	protected $comment = '';
	protected $optional = false;
	protected $default = null;

	public function __construct($rule)
	{
		if (isset($rule['comment'])) {
			$this->comment = $rule['comment'];
		}
		if (isset($rule['optional'])) {
			$this->optional = $rule['optional'];
		}
		if (isset($rule['default'])) {
			$this->default = $rule['default'];
		}
	}

	/**
	 * hash の必須設定を参照するためのインターフェース
	 * @return bool
	 */
	public function isOptional()
	{
		return $this->optional;
	}

	public function dump()
	{
		return [
			'type' => str_replace(__NAMESPACE__ . '\\', '', str_replace('Rule', '', get_called_class())),
			'comment' => $this->comment,
			'optional' => $this->optional,
		];
	}

	public function getDefault()
	{
		return $this->default;
	}
}
