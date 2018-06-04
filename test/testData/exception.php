<?php
/**
 * Created by PhpStorm.
 * User: 745b
 * Date: 2018/01/10
 * Time: 10:35
 */

namespace testData;

use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;

class exception implements \mihoshi\hashValidator\interfaces\ruleInterface
{
	public function __construct($rule)
	{
//		parent::__construct($rule);
        throw new \Exception('hoge',999);
	}

	public function check($value)
	{
		// TODO: Implement check() method.
		return 'hogehoge';
	}

	public function isOptional()
	{
		// TODO: Implement isOptional() method.
	}

	public function dump()
	{
		// TODO: Implement dump() method.
	}

	public function getDefault()
	{
		// TODO: Implement getDefault() method.
	}

}
