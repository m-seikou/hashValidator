<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidDataException;

class floatRule extends abstractRule
{
	private $min = null;
	private $max = null;

	public function __construct($rule)
	{
		parent::__construct($rule);
		if (isset($rule['min'])) {
			$this->min = $rule['min'];
		}
		if (isset($rule['max'])) {
			$this->max = $rule['max'];
		}
	}

	public function check($value)
	{
		if (!is_numeric($value)) {
			throw new invalidDataException('invalid int value:' . var_export($value, true));
		}
		$value = (float)$value;
		if (!is_null($this->min) && $value < $this->min) {
			throw new invalidDataException('input:' . $value . ' less than ' . $this->min);
		}
		if (!is_null($this->max) && $value > $this->max) {
			throw new invalidDataException('input:' . $value . ' grater than ' . $this->max);
		}
		return $value;
	}

	public function dump()
	{
		return array_merge(parent::dump(), [
			'min' => $this->min,
			'max' => $this->max,
		]);
	}


}
