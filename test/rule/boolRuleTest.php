<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:02
 */

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\boolRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

class boolRuleTest extends hashValidatorTestCase
{
    public function dataPass()
    {
        yield [true];
        yield [false];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPass($data)
    {
        $validator = new boolRule([]);
        $this->assertEquals($data, $validator->check($data));
    }

    public function dataFail()
    {
        yield ['a'];
        yield [[]];
        yield [new \stdClass()];
        yield [0];
        yield [1];
        yield [null];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testFail($data)
    {
        $validator = new boolRule([]);
        $validator->check($data);
    }

    public function testArrowNull()
    {
        $validator = new boolRule(['arrow_null' => true]);
        $this->assertNull($validator->check(null));
    }
}
