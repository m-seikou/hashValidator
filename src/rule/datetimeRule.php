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

class datetimeRule extends abstractRule
{
    private $timezone;
    private $format;
    private $min;
    private $max;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (isset($rule['timezone'])) {
            $this->timezone = new \DateTimeZone($rule['timezone']);
        } else {
            $this->timezone = new \DateTimeZone(date_default_timezone_get());
        }
        if (isset($rule['format'])) {
            $this->format = $rule['format'];
        } else {
            $this->format = \DateTime::ATOM;
        }
        if (isset($rule['min'])) {
            try {
                $this->min = new \DateTimeImmutable($rule['min']);
            } catch (\Exception $e) {
                throw new invalidRuleException($e->getMessage(), $e->getCode(), $e);
            }
        }
        if (isset($rule['max'])) {
            try {
                $this->max = new \DateTimeImmutable($rule['max']);
            } catch (\Exception $e) {
                throw new invalidRuleException($e->getMessage(), $e->getCode(), $e);
            }
        }
    }

    public function check($value)
    {
        try {
            $datetime = new \DateTimeImmutable($value, $this->timezone);
        } catch (\Exception $e) {
            throw new invalidDataException($e->getMessage(), $e->getCode(), $e);
        }
        if ($this->min && $datetime < $this->min) {
            throw new invalidDataException('out of range:' . $value . ' less than ' . $this->min->format($this->format));
        }
        if ($this->max && $datetime > $this->max) {
            throw new invalidDataException('out of range:' . $value . ' grater than ' . $this->max->format($this->format));
        }
        return $datetime->format($this->format);
    }

    public function dump(): array
    {
        $return = array_merge(parent::dump(), [
            'timezone' => $this->timezone->getName(),
            'format' => $this->format,
            'type' => 'datetime',
        ]);
        if ($this->min) {
            $return['min'] = $this->min->format($this->format);
        }
        if ($this->min) {
            $return['max'] = $this->max->format($this->format);
        }
        return $return;
    }

}
