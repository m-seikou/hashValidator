<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\stringRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

class stringRuleTest extends hashValidatorTestCase
{
    public function dataPass()
    {
        yield [''];
        yield ['hogehogehogehoge'];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPass($data)
    {
        $validator = new stringRule([]);
        $this->assertEquals($data, $validator->check($data));
    }

    public function dataFail()
    {
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
        $validator = new stringRule([]);
        $validator->check($data);
    }


    public function dataLengthPass()
    {
        yield ['333'];
        yield ['55555'];
    }

    /**
     * @param $data
     * @dataProvider dataLengthPass
     */
    public function testLengthPass($data)
    {
        $validator = new stringRule(['max' => 5, 'min' => 2]);
        $this->assertEquals($data, $validator->check($data));
    }


    public function dataLengthFail()
    {
        yield ['2'];
        yield ['666666'];
    }

    /**
     * @param $data
     * @dataProvider dataLengthFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testLengthFail($data)
    {
        $validator = new stringRule(['max' => 5, 'min' => 2]);
        $validator->check($data);
    }

    public function dataPregPass()
    {
        yield ['hogehoge'];
        yield ['aaaahogehogeffuuuu'];
        yield ['aaaaaaaaaahogehoge'];
    }

    /**
     * @param $data
     * @dataProvider dataPregPass
     */
    public function testPregPass($data)
    {
        $validator = new stringRule(['preg' => '/hogehoge/']);
        $this->assertEquals($data, $validator->check($data));
    }


    public function dataPregFail()
    {
        yield ['hogeahoge'];
    }

    /**
     * @param $data
     * @dataProvider dataPregFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testPregFail($data)
    {
        $validator = new stringRule(['preg' => '/hogehoge/']);
        $validator->check($data);
    }

    public function testArrowNull(){
        $validator = new stringRule(['arrow_null' => true]);
        $this->assertNull($validator->check(null));
    }

    public function testDump()
    {
        $rule = new stringRule([]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertNull($rule->dump()['max']);
        $this->assertNull($rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

    }
    public function testDumpWithMaxMin()
    {
        $rule = new stringRule([
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
