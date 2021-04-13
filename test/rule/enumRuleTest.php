<?php

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;
use mihoshi\hashValidator\rule\enumRule;
use stdClass;

class enumRuleTest extends hashValidatorTestCase
{
    public function dataDefine(): Generator
    {
        yield '空定義(配列)' => [[]];
        yield '空定義(stdclass)' => [new stdClass()];
    }

    /**
     * @dataProvider dataDefine
     */
    public function testDefine($value): void
    {
        $this->expectException(invalidRuleException::class);
        new enumRule(['value' => $value]);
    }

    public function dataPass(): Generator
    {
        yield [2];
        yield [4];
        yield [6];
        yield [8];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPass($data): void
    {
        $validator = new enumRule(['value' => [2, 4, 6, 8]]);
        self::assertEquals($data, $validator->check($data), $data);
    }

    public function dataFail(): Generator
    {
        yield [1];
        yield [3];
        yield [5];
        yield [7];
        yield [9];
        yield [[]];
        yield [new stdClass()];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     */
    public function testFail($data): void
    {
        $validator = new enumRule(['value' => [2, 4, 6, 8]]);
        $this->expectException(invalidDataException::class);
        $validator->check($data);
    }


    public function testDump(): void
    {
        $rule = new enumRule(['value' => [1]]);
        self::assertArrayHasKey('type', $rule->dump());
        self::assertEquals('enum', $rule->dump()['type']);
        self::assertArrayHasKey('value', $rule->dump());
        self::assertEquals([1], $rule->dump()['value']);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(false, $rule->dump()['optional']);

        $rule = new enumRule([
            'value' => [1],
            'comment' => 'hogehoge',
            'optional' => true,
        ]);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('hogehoge', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(true, $rule->dump()['optional']);
    }

    public function testToText():void
    {
        $rule = new enumRule(['value' => [1]]);
        self::assertStringStartsWith(' ', $rule->toText('    ', 1));
    }
}
