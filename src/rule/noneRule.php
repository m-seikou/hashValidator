<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;
use Closure;

class noneRule extends abstractRule
{
	public function check($value)
	{
		return $value;
	}

    public function dump(?Closure $closure = null):array
    {
        return array_merge(parent::dump($closure), [
            'type' => 'none',
        ]);
    }
}
