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
        yield '未定義のキーは削除される' => [
            ['key' => ['hoge' => ['type' => 'int', 'optional' => true]]],
            ['fuga' => 1],
            [],
        ];
        yield 'デフォルト値が設定されている未定義項目はデフォルトの値となる' =>[
            ['key' => ['hoge' => ['type' => 'int', 'default' => 42]]],
            [],
            ['hoge' => 42],
        ];
        yield '再帰的にチェックされている' =>[
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
        yield '必須のキーがない' => [
            ['key' => ['hoge' => ['type' => 'int']]],
            ['fuga' => 1],
        ];
        yield 'キーで指定した型と一致しない' => [
            ['key' => ['hoge' => ['type' => 'int']]],
            ['hoge' => 'aa'],
        ];
        yield '再帰的にエラーを補足している' => [
            ['key' => ['hoge' => ['type' => 'hash', 'key' => ['fuga' => ['type' => 'int',],],],],],
            ['hoge' => ['fuga' => 'bb']],
        ];
        yield '配列じゃない' => [
            ['key' => ['hoge' => ['type' => 'int']]],
            '{"hoge":2}',
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
