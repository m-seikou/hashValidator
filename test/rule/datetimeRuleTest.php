<?php
/**
 * Created by PhpStorm.
 * User: 745b
 * Date: 2017/10/02
 * Time: 16:26
 */

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\datetimeRule;

class datetimeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct_empty()
    {
        $rule = (new datetimeRule([]))->dump();
        $this->assertEquals('datetime', $rule['type']);
        $this->assertEquals(\DateTime::ATOM, $rule['format']);
        $this->assertEquals(date_default_timezone_get(), $rule['timezone']);
    }

    public function testConstruct_full()
    {
        $rule = (new datetimeRule([
            'format' => 'Y-m-d H:i:s',
            'timezone' => 'Asia/Tokyo',
            'min' => '2016/01/01',
            'max' => '2016/12/31',
        ]))->dump();
        $this->assertEquals('Y-m-d H:i:s', $rule['format']);
        $this->assertEquals('Asia/Tokyo', $rule['timezone']);
        $this->assertEquals('2016-01-01 00:00:00', $rule['min']);
        $this->assertEquals('2016-12-31 00:00:00', $rule['max']);
    }

    /**
     * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     */
    public function testConstruct_invalidFormat_min()
    {
        new datetimeRule(['min' => 'hogehoge']);
    }

    /**
	 * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     */
    public function testConstruct_invalidFormat_max()
    {
        new datetimeRule(['max' => 'hogehoge']);
    }

    /**
	 * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testCheck_fail_invalid_format()
    {
        $result = (new datetimeRule([]))->check('hogehoge');
    }

    /**
	 * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testCheck_fail_less_than_min()
    {
        (new datetimeRule(['format' => 'Y-m-d H:i:s', 'min' => '2017-10-01 12:00:00']))->check('2017-10-01 11:59:59');
    }

    public function testCheck_pass_equals_min()
    {
        $result = (new datetimeRule(['format' => 'Y-m-d H:i:s', 'min' => '2017-10-01 12:00:00']))->check('2017-10-01 12:00:00');
        $this->assertEquals('2017-10-01 12:00:00', $result);
    }

    public function testCheck_pass_grater_than_min()
    {
        $result = (new datetimeRule(['format' => 'Y-m-d H:i:s', 'min' => '2017-10-01 12:00:00']))->check('2017-10-01 12:00:01');
        $this->assertEquals('2017-10-01 12:00:01', $result);
    }

    public function testCheck_pass_less_than_max()
    {
        $result = (new datetimeRule(['format' => 'Y-m-d H:i:s', 'max' => '2017-10-01 12:00:00']))->check('2017-10-01 11:59:59');
        $this->assertEquals('2017-10-01 11:59:59', $result);
    }
    public function testCheck_pass_equal_max()
    {
        $result = (new datetimeRule(['format' => 'Y-m-d H:i:s', 'max' => '2017-10-01 12:00:00']))->check('2017-10-01 12:00:00');
        $this->assertEquals('2017-10-01 12:00:00', $result);
    }

    /**
	 * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testCheck_fail_grater_than_max()
    {
        (new datetimeRule(['format' => 'Y-m-d H:i:s', 'max' => '2017-10-01 12:00:00']))->check('2017-10-01 12:00:01');
    }
}
