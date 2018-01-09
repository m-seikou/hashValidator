<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;


class noneRule extends abstractRule
{
	public function check($value)
	{
		return $value;
	}

}
