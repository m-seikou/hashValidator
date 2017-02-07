<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\intRule;

class intRuleTest extends hashValidatorTestCase
{
    public function dataPass()
    {
        yield [-PHP_INT_MAX, -PHP_INT_MAX];
        yield [0, 0];
        yield [PHP_INT_MAX, PHP_INT_MAX];
        yield ['12345', 12345];
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider dataPass
     */
    public function testPass($data, $expected)
    {
        $validator = new intRule([]);
        $this->assertEquals($expected, $validator->check($data));
    }

    public function dataFail()
    {
        yield ['a'];
        yield [[]];
        yield [new \stdClass()];
        yield [null];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testFail($data)
    {
        $validator = new intRule([]);
        $validator->check($data);
    }

    public function dataRangePass()
    {
        yield [2, 2];
        yield [3, 3];
        yield [9, 9];
        yield [10, 10];
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider dataRangePass
     */
    public function testRangePass($data, $expected)
    {
        $validator = new intRule(['max' => 10, 'min' => 2]);
        $this->assertEquals($expected, $validator->check($data));
    }

    public function dataRangeFail()
    {
        yield [0];
        yield [1];
        yield [11];
        yield [12];
    }

    /**
     * @param $data
     * @dataProvider dataRangeFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testRangeFail($data){
        $validator = new intRule(['max' => 10, 'min' => 2]);
        $validator->check($data);
    }

    public function testArrowNull(){
        $validator = new intRule(['arrow_null' => true]);
        $this->assertNull($validator->check(null));
    }

    public function testDump()
    {
        $rule = new intRule([]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertEquals(PHP_INT_MAX, $rule->dump()['max']);
        $this->assertArrayHasKey('min', $rule->dump());
        $this->assertEquals(-PHP_INT_MAX, $rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new intRule([
            'max' => 100,
            'min' => 10,
            'comment' => 'hogehoge',
            'optional' => true,
        ]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertEquals(100, $rule->dump()['max']);
        $this->assertArrayHasKey('min', $rule->dump());
        $this->assertEquals(10, $rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('hogehoge', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(true, $rule->dump()['optional']);
    }

}
