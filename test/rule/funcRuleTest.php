<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\funcRule;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;

class funcRuleTest extends hashValidatorTestCase
{
    public function testDefine()
    {
        foreach (['', 'hogehoge', [], new \stdClass()] as $value) {
            try {
                new funcRule([$value]);
                $this->fail();
            } catch (invalidRuleException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public static function callbackEcho($arg)
    {
        return $arg;
    }

    public static function callbackThrow($arg)
    {
        throw new \Exception($arg);
    }

    public static function callbackInstance($arg){
        return new \stdClass();
    }

    public function testValue()
    {
        $validator = new funcRule(['class' => __CLASS__, 'method' => 'callbackEcho']);

        $this->assertTrue($validator->check(true));
        $this->assertFalse($validator->check(false));

        $validator = new funcRule(['class' => __CLASS__, 'method' => 'callbackThrow']);
        try {
            $validator->check(false);
            $this->fail();
        } catch (invalidDataException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        $validator = new funcRule(['class' => __CLASS__, 'method' => 'callbackInstance']);

        $this->assertInstanceOf('stdClass',$validator->check(false));

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
            'comment'  => 'hogehoge',
            'optional' => true,
        ]);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('hogehoge', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(true, $rule->dump()['optional']);
    }

}
