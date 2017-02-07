<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\floatRule;

class floatRuleTest extends hashValidatorTestCase
{
    public function dataPass()
    {
        yield [-PHP_INT_MAX, 0, PHP_INT_MAX, '12345',];
        yield [-PHP_INT_MAX];
        yield [PHP_INT_MAX];
        yield ['12345'];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPass($data)
    {
        $validator = new floatRule([]);
        $this->assertEquals($data, $validator->check($data));
    }

    public function dataFail()
    {
        yield ['a'];
        yield [[]];
        yield [new \stdClass()];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testFail($data)
    {
        $validator = new floatRule([]);
        $validator->check($data);
    }

    public function dataRangePass()
    {
        yield [2.828];
        yield [3];
        yield [3.141];
    }

    /**
     * @param $data
     * @dataProvider dataRangePass
     */
    public function testRangePass($data)
    {
        $validator = new floatRule(['max' => 3.141, 'min' => 2.828]);
        $this->assertEquals($data, $validator->check($data));
    }

    public function dataRangeFail()
    {
        yield [2.827999999];
        yield [3.1410001];
    }

    /**
     * @param $data
     * @dataProvider dataRangeFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testRangeFail($data)
    {
        $validator = new floatRule(['max' => 3.141, 'min' => 2.828]);
        $validator->check($data);
    }

    public function testDump()
    {
        $rule = new floatRule([]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertNull($rule->dump()['max']);
        $this->assertNull($rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new floatRule([
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
