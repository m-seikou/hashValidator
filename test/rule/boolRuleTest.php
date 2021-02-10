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
    public function testPassStrict($data)
    {
        $validator = new boolRule([]);
        self::assertEquals($data, $validator->check($data));
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
     */
    public function testFailStrict($data)
    {
        $this->expectException(invalidDataException::class);
        $validator = new boolRule([]);
        $validator->check($data);
    }

    public function testArrowNull()
    {
        $validator = new boolRule(['arrow_null' => true]);
        $this->assertNull($validator->check(null));
    }

    /**
     * @param $data
     * @dataProvider  dataFail
     */
    public function testFail($data){
        $validator = new boolRule(['strict'=>false]);
        $result = $validator->check($data);
        self::assertContains($result,[true,false]);
    }
}
