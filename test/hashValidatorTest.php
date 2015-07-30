<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/16
 * Time: 10:37
 */

namespace mihoshi\hashValidator;

include_once 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));


class hashValidatorTest extends hashValidatorTestCase
{

    public function testGetDefine()
    {
        $validator = new hashValidator(['type' => 'hash', 'value' => []], hashValidator::DEFINE_ARRAY);
        $this->assertEquals(['type' => 'hash', 'value' => []], $validator->getDefine());
    }


    public function testEnum()
    {
        $validator = new hashValidator(['type' => 'enum', 'value' => [2, 4, 6, 8]], hashValidator::DEFINE_ARRAY);
        foreach ([2, 4, 6, 8] as $data) {
            $this->assertEquals($data, $validator->validate($data), $data);
        }
        foreach ([1, 3, 5, 7, 9] as $data) {
            try {
                $validator->validate($data);
                $this->fail();
            } catch (hashValidatorException $e) {
                echo $e->getMessage() . PHP_EOL;
                $this->assertEquals(hashValidator::ERR_INVALID_VALUE, $e->getCode());
            }
        }

    }

    public function testHash()
    {
        $def = ['type' => 'hash', 'value' => ['key' => ['type' => 'int'],]];
        $validator = new hashValidator($def, hashValidator::DEFINE_ARRAY);
        foreach ([[], ['key' => 'hoge']] as $data) {
            try {
                $validator->validate($data);
                $this->fail($data);
            } catch (hashValidatorException $e) {
                echo $e->getMessage() . PHP_EOL;
                $this->assertEquals(hashValidator::ERR_INVALID_VALUE, $e->getCode());
            }
        }
        $this->assertSame($validator->validate(['key' => 10]), ['key' => 10]);
        $this->assertSame($validator->validate(['key' => 10, 'hoge' => 'fuga']), ['key' => 10]);
    }

    public function testHashOptional()
    {
        $def = ['type' => 'hash', 'value' => ['key' => ['type' => 'int', 'optional' => true],]];
        $validator = new hashValidator($def, hashValidator::DEFINE_ARRAY);

        try {
            $validator->validate(['key' => 'fuga']);
            $this->fail();
        } catch (hashValidatorException $e) {
            echo $e->getMessage() . PHP_EOL;
            $this->assertEquals(hashValidator::ERR_INVALID_VALUE, $e->getCode());
        }

        $this->assertSame($validator->validate([]), []);
        $this->assertSame($validator->validate(['key' => 10]), ['key' => 10]);
        $this->assertSame($validator->validate(['key' => 10, 'hoge' => 'fuga']), ['key' => 10]);

    }

    public static function callbackEcho($arg)
    {
        return $arg;
    }

    public static function callbackThrow($arg)
    {
        throw new \Exception($arg);
    }

    public function testCallBack()
    {
        $def = ['type' => 'callback', 'value' => [__CLASS__, 'callbackEcho']];
        $validator = new hashValidator($def, hashValidator::DEFINE_ARRAY);

        $this->assertTrue($validator->validate(true));
        try {
            $validator->validate(false);
            $this->fail();
        } catch (hashValidatorException $e) {
            echo $e->getMessage() . PHP_EOL;
            $this->assertEquals(hashValidator::ERR_INVALID_VALUE, $e->getCode());
        }

        $def = ['type' => 'callback', 'value' => [__CLASS__, 'callbackThrow']];
        $validator = new hashValidator($def, hashValidator::DEFINE_ARRAY);
        try {
            $validator->validate(false);
            $this->fail();
        } catch (hashValidatorException $e) {
            echo $e->getMessage() . PHP_EOL;
            $this->assertEquals(hashValidator::ERR_INVALID_VALUE, $e->getCode());
        }

    }

    public function testList()
    {
        $def = ['type' => 'list', 'value' => ['type' => 'int'], 'min' => 1, 'max' => 2];
        $validator = new hashValidator($def, hashValidator::DEFINE_ARRAY);
        foreach ([[], [1, 2, 3]] as $data) {
            try {
                $validator->validate($data);
                $this->fail($data);
            } catch (hashValidatorException $e) {
                echo $e->getMessage() . PHP_EOL;
                $this->assertEquals(hashValidator::ERR_INVALID_VALUE, $e->getCode());
            }
        }
        foreach ([[1], [1, 2], ['a' => 3]] as $data) {
            $this->assertSame($data, $validator->validate($data));
        }

    }
}