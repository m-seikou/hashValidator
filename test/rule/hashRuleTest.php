<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\hashRule;

class hashRuleTest extends hashValidatorTestCase
{

    public function dataConstructError()
    {
        yield 'undefined "key"' => [[]];
        yield '"key" is not array' => [['key' => 0]];
    }

    /**
     * @param $rule
     * @dataProvider dataConstructError
     * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     */
    public function testConstructError($rule)
    {
        new hashRule($rule);
    }

    public function dataPass()
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
    public function testPass($define, $data, $excepted)
    {
        $validator = new hashRule($define);
        $this->assertSame($excepted, $validator->check($data));
    }

    public function dataFail()
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
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testFail($define, $data)
    {
        $validator = new hashRule($define);
        $validator->check($data);
    }

    public function testDump()
    {
        $result = (new hashRule(['key' => ['hoge' => ['type' => 'int']]]))->dump();
        $this->assertArrayHasKey('key',$result);
    }

}
