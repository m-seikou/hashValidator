<?php

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;
use mihoshi\hashValidator\rule\hashRule;

class hashRuleTest extends hashValidatorTestCase
{

    public function dataConstructError(): Generator
    {
        yield 'undefined "key"' => [[]];
        yield '"key" is not array' => [['key' => 0]];
    }

    /**
     * @param $rule
     * @dataProvider dataConstructError
     */
    public function testConstructError($rule): void
    {
        $this->expectException(invalidRuleException::class);
        new hashRule($rule);
    }

    public function dataPass(): Generator
    {
        yield [
            ['key' => ['hoge' => ['type' => 'int']]],
            ['hoge' => 10],
            ['hoge' => 10],
        ];
        yield [
            ['key' => ['hoge' => ['type' => 'int', 'optional' => true]]],
            ['fuga' => 1],
            [],
        ];
        yield [
            ['key' => ['hoge' => ['type' => 'int', 'default' => 42]]],
            [],
            ['hoge' => 42],
        ];
        yield [
            ['key' => ['hoge' => ['type' => 'hash', 'key' => ['fuga' => ['type' => 'int',],],],],],
            ['hoge' => ['fuga' => 1]],
            ['hoge' => ['fuga' => 1]],
        ];
    }

    /**
     * @param $define
     * @param $data
     * @param $excepted
     * @dataProvider dataPass
     */
    public function testPass($define, $data, $excepted): void
    {
        $validator = new hashRule($define);
        self::assertSame($excepted, $validator->check($data));
    }

    public function dataFail(): Generator
    {
        yield [
            ['key' => ['hoge' => ['type' => 'int']]],
            ['fuga' => 1],
        ];
        yield [
            ['key' => ['hoge' => ['type' => 'int']]],
            ['hoge' => 'aa'],
        ];
        yield [
            ['key' => ['hoge' => ['type' => 'hash', 'key' => ['fuga' => ['type' => 'int',],],],],],
            ['hoge' => ['fuga' => 'bb']],
        ];
    }

    /**
     * @param $define
     * @param $data
     * @dataProvider dataFail
     */
    public function testFail($define, $data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new hashRule($define);
        $validator->check($data);
    }

    public function testDump(): void
    {
        $result = (new hashRule(['key' => ['hoge' => ['type' => 'int']]]))->dump();
        self::assertArrayHasKey('key', $result);
    }

}
