<?php

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\rule\intRule;

class intRuleTest extends hashValidatorTestCase
{
    public function dataPass(): Generator
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
    public function testPass($data, $expected): void
    {
        $validator = new intRule([]);
        self::assertEquals($expected, $validator->check($data));
    }

    public function dataFail(): Generator
    {
        yield ['a'];
        yield [[]];
        yield [new \stdClass()];
        yield [null];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     */
    public function testFail($data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new intRule([]);
        $validator->check($data);
    }

    public function dataRangePass(): Generator
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
    public function testRangePass($data, $expected): void
    {
        $validator = new intRule(['max' => 10, 'min' => 2]);
        self::assertEquals($expected, $validator->check($data));
    }

    public function dataRangeFail(): Generator
    {
        yield [0];
        yield [1];
        yield [11];
        yield [12];
    }

    /**
     * @param $data
     * @dataProvider dataRangeFail
     */
    public function testRangeFail($data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new intRule(['max' => 10, 'min' => 2]);
        $validator->check($data);
    }

    public function testArrowNull(): void
    {
        $validator = new intRule(['arrow_null' => true]);
        self::assertNull($validator->check(null));
    }

    public function testDump(): void
    {
        $rule = new intRule([]);
        self::assertArrayHasKey('max', $rule->dump());
        self::assertEquals(PHP_INT_MAX, $rule->dump()['max']);
        self::assertArrayHasKey('min', $rule->dump());
        self::assertEquals(-PHP_INT_MAX, $rule->dump()['min']);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(false, $rule->dump()['optional']);

        $rule = new intRule([
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
