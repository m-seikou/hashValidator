<?php
/**
 * Created by PhpStorm.
 * User: 745b
 * Date: 2017/10/02
 * Time: 16:26
 */

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;
use mihoshi\hashValidator\rule\datetimeRule;
use PHPUnit\Framework\TestCase;

class datetimeRuleTest extends TestCase
{
    public function testConstruct_empty()
    {
        $rule = (new datetimeRule([]))->dump();
        self::assertEquals('datetime', $rule['type']);
        self::assertEquals(\DateTime::ATOM, $rule['format']);
        self::assertEquals(date_default_timezone_get(), $rule['timezone']);
    }

    public function testConstruct_full():void
    {
        $rule = (new datetimeRule([
            'format' => 'Y-m-d H:i:s',
            'timezone' => 'Asia/Tokyo',
            'min' => '2016/01/01',
            'max' => '2016/12/31',
        ]))->dump();
        self::assertEquals('Y-m-d H:i:s', $rule['format']);
        self::assertEquals('Asia/Tokyo', $rule['timezone']);
        self::assertEquals('2016-01-01 00:00:00', $rule['min']);
        self::assertEquals('2016-12-31 00:00:00', $rule['max']);
    }

    /**
     *
     */
    public function testConstruct_invalidFormat_min():void
    {
        $this->expectException(invalidRuleException::class);
        new datetimeRule(['min' => 'hogehoge']);
    }

    /**
     *
     */
    public function testConstruct_invalidFormat_max():void
    {
        $this->expectException(invalidRuleException::class);
        new datetimeRule(['max' => 'hogehoge']);
    }

    /**
     *
     */
    public function testCheck_fail_invalid_format():void
    {
        $this->expectException(invalidDataException::class);
        $result = (new datetimeRule([]))->check('hogehoge');
    }

    /**
     *
     */
    public function testCheck_fail_less_than_min():void
    {
        $this->expectException(invalidDataException::class);
        (new datetimeRule(['format' => 'Y-m-d H:i:s', 'min' => '2017-10-01 12:00:00']))->check('2017-10-01 11:59:59');
    }

    public function testCheck_pass_equals_min():void
    {
        $result = (new datetimeRule([
            'format' => 'Y-m-d H:i:s',
            'min' => '2017-10-01 12:00:00',
        ]))->check('2017-10-01 12:00:00');
        self::assertEquals('2017-10-01 12:00:00', $result);
    }

    public function testCheck_pass_grater_than_min():void
    {
        $result = (new datetimeRule([
            'format' => 'Y-m-d H:i:s',
            'min' => '2017-10-01 12:00:00',
        ]))->check('2017-10-01 12:00:01');
        self::assertEquals('2017-10-01 12:00:01', $result);
    }

    public function testCheck_pass_less_than_max():void
    {
        $result = (new datetimeRule([
            'format' => 'Y-m-d H:i:s',
            'max' => '2017-10-01 12:00:00',
        ]))->check('2017-10-01 11:59:59');
        self::assertEquals('2017-10-01 11:59:59', $result);
    }

    public function testCheck_pass_equal_max():void
    {
        $result = (new datetimeRule([
            'format' => 'Y-m-d H:i:s',
            'max' => '2017-10-01 12:00:00',
        ]))->check('2017-10-01 12:00:00');
        self::assertEquals('2017-10-01 12:00:00', $result);
    }

    /**
     *
     */
    public function testCheck_fail_grater_than_max():void
    {
        $this->expectException(invalidDataException::class);
        (new datetimeRule(['format' => 'Y-m-d H:i:s', 'max' => '2017-10-01 12:00:00']))->check('2017-10-01 12:00:01');
    }
}
