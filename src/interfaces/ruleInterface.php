<?php

namespace mihoshi\hashValidator\interfaces;

use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;

interface ruleInterface
{

	/**
	 * ruleInterface constructor.
	 * @param $rule
	 * @throws invalidRuleException
	 */
	public function __construct($rule);

	/**
	 * @param $value
	 * @return mixed バリデーションをかけた値
	 * @throws invalidDataException バリデーションを通過できない場合に発生する例外
	 */
	public function check($value);

	/**
	 * hashの必須設定を参照するためのインターフェース
	 * @return bool
	 */
	public function isOptional();

	public function dump();

	public function getDefault();
}
