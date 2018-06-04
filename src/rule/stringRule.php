<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidDataException;

final class stringRule extends abstractRule
{
	private $min = null;
	private $max = null;
	private $preg = null;
	private $null = false;

	public function __construct($rule)
	{
		parent::__construct($rule);
		if (isset($rule['min'])) {
			$this->min = $rule['min'];
		}
		if (isset($rule['max'])) {
			$this->max = $rule['max'];
		}
		if (isset($rule['preg'])) {
			$this->preg = $rule['preg'];
		}
		if (isset($rule['arrow_null'])) {
			$this->null = $rule['arrow_null'];
		}
	}

	public function check($value)
	{
		if (is_null($value) && $this->null) {
			return null;
		}
		if (!is_scalar($value)) {
			throw new invalidDataException('invalid string value:' . var_export($value, true));
		}
		$value = (string)$value;
		$len = strlen($value);
		if (isset($this->min) && $len < $this->min) {
			throw new invalidDataException('input length:' . $len . ' less than ' . $this->min);
		}
		if (isset($this->max) && $len > $this->max) {
			throw new invalidDataException('input length:' . $len . ' grater than ' . $this->max);
		}
		if (isset($this->preg) && !preg_match($this->preg, $value)) {
			throw new invalidDataException('input:' . $value . ' not match ' . $this->preg);
		}
		return $value;
	}

	public function dump()
	{
		return array_merge(parent::dump(), [
			'min' => $this->min,
			'max' => $this->max,
			'preg' => $this->preg,
		]);
	}
}
