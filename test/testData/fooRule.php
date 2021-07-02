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

class fooRule implements \mihoshi\hashValidator\interfaces\ruleInterface
{
	public function __construct($rule)
	{
//		parent::__construct($rule);
	}

	public function check($value)
	{
		return 'hogehoge';
	}

	public function isOptional():bool
	{
        return false;
	}

	public function dump():array
	{
        return [];
	}

	public function getDefault()
	{
		// TODO: Implement getDefault() method.
	}

}
