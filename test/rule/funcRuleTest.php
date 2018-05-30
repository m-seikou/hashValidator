<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\funcRule;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;

class funcRuleTest extends hashValidatorTestCase
{
    public function dataDefine()
    {
        yield [''];
        yield ['hogehoge'];
        yield [[]];
        yield [new \stdClass()];
    }

    /**
     * @param $data
     * @dataProvider dataDefine
     * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     */
    public function testDefine($data)
    {
        new funcRule([$data]);
    }

    /**
     * 検証用関数 受け取った値を返す
     * @param $arg
     * @return mixed
     */
    public static function callbackEcho($arg)
    {
        return $arg;
    }

    /**
     * 検証用関数 例外を投げる
     * @param $arg
     * @throws \Exception
     */
    public static function callbackThrow($arg)
    {
        throw new \Exception($arg);
    }

    /**
     * 検証用関数 stdClassをかえす
     * @param $arg
     * @return \stdClass
     */
    public static function callbackInstance($arg)
    {
        return new \stdClass();
    }

    public function dataPass()
    {
        yield [['class' => __CLASS__, 'method' => 'callbackEcho'], true, true];
        yield [['class' => __CLASS__, 'method' => 'callbackEcho'], false, false];
    }

    /**
     * @param $define
     * @param $data
     * @param $expected
     * @dataProvider dataPass
     */
    public function testPass($define, $data, $expected)
    {
        $validator = new funcRule($define);
        $this->assertSame($expected, $validator->check($data));
    }

    public function dataFail()
    {
        yield [['class' => __CLASS__, 'method' => 'callbackThrow'], false];
    }

    /**
     * @param $define
     * @param $data
     * @dataProvider dataFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testFail($define, $data)
    {
        $validator = new funcRule($define);
        $validator->check($data);
    }

    public function testInstance()
    {
        $validator = new funcRule(['class' => __CLASS__, 'method' => 'callbackInstance']);
        $this->assertInstanceOf('stdClass', $validator->check(false));
    }


    public function testDump()
    {
        $rule = new funcRule(['function' => 'is_array']);
        $this->assertArrayHasKey('type', $rule->dump());
        $this->assertEquals('is_array', $rule->dump()['function']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new funcRule([
            'function' => 'is_array',
            'comment' => 'hogehoge',
            'optional' => true,
        ]);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('hogehoge', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(true, $rule->dump()['optional']);
    }

}
