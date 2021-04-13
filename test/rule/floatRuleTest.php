<?php

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\rule\floatRule;
use stdClass;

class floatRuleTest extends hashValidatorTestCase
{
    public function dataPass(): Generator
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
    public function testPass($data):void
    {
        $validator = new floatRule([]);
        self::assertEquals($data, $validator->check($data));
    }

    public function dataFail(): Generator
    {
        yield ['a'];
        yield [[]];
        yield [new stdClass()];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     */
    public function testFail($data):void
    {
        $this->expectException(invalidDataException::class);
        $validator = new floatRule([]);
        $validator->check($data);
    }

    public function dataRangePass(): Generator
    {
        yield [2.828];
        yield [3];
        yield [3.141];
    }

    /**
     * @param $data
     * @dataProvider dataRangePass
     */
    public function testRangePass($data):void
    {
        $validator = new floatRule(['max' => 3.141, 'min' => 2.828]);
        self::assertEquals($data, $validator->check($data));
    }

    public function dataRangeFail(): Generator
    {
        yield [2.827999999];
        yield [3.1410001];
    }

    /**
     * @param $data
     * @dataProvider dataRangeFail
     */
    public function testRangeFail($data):void
    {
        $this->expectException(invalidDataException::class);
        $validator = new floatRule(['max' => 3.141, 'min' => 2.828]);
        $validator->check($data);
    }

    public function testDump():void
    {
        $rule = new floatRule([]);
        self::assertArrayHasKey('max', $rule->dump());
        self::assertNull($rule->dump()['max']);
        self::assertNull($rule->dump()['min']);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(false, $rule->dump()['optional']);

        $rule = new floatRule([
            'max' => 100,
            'min' => 10,
            'comment' => 'hogehoge',
            'optional' => true,
        ]);
        self::assertArrayHasKey('max', $rule->dump());
        self::assertEquals(100, $rule->dump()['max']);
        self::assertArrayHasKey('min', $rule->dump());
        self::assertEquals(10, $rule->dump()['min']);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('hogehoge', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(true, $rule->dump()['optional']);
    }

}
