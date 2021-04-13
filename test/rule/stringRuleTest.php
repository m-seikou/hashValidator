<?php

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\rule\stringRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

class stringRuleTest extends hashValidatorTestCase
{
    public function dataPass(): Generator
    {
        yield [''];
        yield ['hogehogehogehoge'];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPass($data): void
    {
        $validator = new stringRule([]);
        self::assertEquals($data, $validator->check($data));
    }

    public function dataFail(): Generator
    {
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
        $validator = new stringRule([]);
        $validator->check($data);
    }


    public function dataLengthPass(): Generator
    {
        yield ['333'];
        yield ['55555'];
    }

    /**
     * @param $data
     * @dataProvider dataLengthPass
     */
    public function testLengthPass($data): void
    {
        $validator = new stringRule(['max' => 5, 'min' => 2]);
        self::assertEquals($data, $validator->check($data));
    }


    public function dataLengthFail(): Generator
    {
        yield ['2'];
        yield ['666666'];
    }

    /**
     * @param $data
     * @dataProvider dataLengthFail
     */
    public function testLengthFail($data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new stringRule(['max' => 5, 'min' => 2]);
        $validator->check($data);
    }

    public function dataPregPass(): Generator
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
        self::assertEquals($data, $validator->check($data));
    }


    public function dataPregFail(): Generator
    {
        yield ['hogeahoge'];
    }

    /**
     * @param $data
     * @dataProvider dataPregFail
     */
    public function testPregFail($data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new stringRule(['preg' => '/hogehoge/']);
        $validator->check($data);
    }

    public function testArrowNull(): void
    {
        $validator = new stringRule(['arrow_null' => true]);
        self::assertNull($validator->check(null));
    }

    public function testDump(): void
    {
        $rule = new stringRule([]);
        self::assertArrayHasKey('max', $rule->dump());
        self::assertNull($rule->dump()['max']);
        self::assertNull($rule->dump()['min']);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(false, $rule->dump()['optional']);

    }

    public function testDumpWithMaxMin(): void
    {
        $rule = new stringRule([
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
