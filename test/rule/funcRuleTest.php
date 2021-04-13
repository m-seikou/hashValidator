<?php

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;
use mihoshi\hashValidator\rule\funcRule;

class funcRuleTest extends hashValidatorTestCase
{
    public function dataDefine(): Generator
    {
        yield [['']];
        yield [['hogehoge']];
        yield [[]];
        yield [[new \stdClass()]];
        yield [['class' => 'hoge', 'method' => 'callbackEcho']];
        yield [['class' => __CLASS__]];
        yield [['class' => __CLASS__, 'method' => 'fuga']];
        yield [['function' => 'fuga']];
    }

    /**
     * @param $data
     * @dataProvider dataDefine
     */
    public function testDefine($data): void
    {
        $this->expectException(invalidRuleException::class);
        new funcRule($data);
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

    public function dataPass(): Generator
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
    public function testPass($define, $data, $expected): void
    {
        $validator = new funcRule($define);
        $this->assertSame($expected, $validator->check($data));
    }

    public function dataFail(): Generator
    {
        yield [['class' => __CLASS__, 'method' => 'callbackThrow'], false];
    }

    /**
     * @param $define
     * @param $data
     * @dataProvider dataFail
     */
    public function testFail($define, $data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new funcRule($define);
        $validator->check($data);
    }

    public function testInstance(): void
    {
        $validator = new funcRule(['class' => __CLASS__, 'method' => 'callbackInstance']);
        self::assertInstanceOf('stdClass', $validator->check(false));
    }


    public function testDump_function(): void
    {
        $rule = new funcRule(['function' => 'is_array']);
        self::assertArrayHasKey('type', $rule->dump());
        self::assertEquals('is_array', $rule->dump()['function']);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(false, $rule->dump()['optional']);
    }

    public function testDump_baseData(): void
    {
        $rule = new funcRule([
            'function' => 'is_array',
            'comment' => 'hogehoge',
            'optional' => true,
        ]);
        self::assertArrayHasKey('comment', $rule->dump());
        self::assertEquals('hogehoge', $rule->dump()['comment']);
        self::assertArrayHasKey('optional', $rule->dump());
        self::assertEquals(true, $rule->dump()['optional']);
    }

    public function testDump_class(): void
    {
        $rule = new funcRule(['class' => __CLASS__, 'method' => 'callbackEcho']);
        self::assertArrayHasKey('class', $rule->dump());
        self::assertEquals(__CLASS__, $rule->dump()['class']);
        self::assertArrayHasKey('method', $rule->dump());
        self::assertEquals('callbackEcho', $rule->dump()['method']);
    }

}
