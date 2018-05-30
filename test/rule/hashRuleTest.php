<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\hashRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

class hashRuleTest extends hashValidatorTestCase
{
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
    public function teatFail($define, $data)
    {
        $validator = new hashRule($define);
        $validator->check($data);
    }

    public function testDump()
    {
    }

}
