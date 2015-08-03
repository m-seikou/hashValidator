<?php

namespace mihoshi\hashValidator;
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class funcRuleTest extends hashValidatorTestCase
{
    public function testDefine()
    {
        foreach (['', 'hogehoge', [], new \stdClass()] as $value) {
            try {
                new funcRule([$value]);
                $this->fail();
            } catch (ruleException $e) {
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

    public function testValue()
    {
        $validator = new funcRule(['class' => __CLASS__, 'method' => 'callbackEcho']);

        $this->assertTrue($validator->check(true));
        try {
            $validator->check(false);
            $this->fail();
        } catch (ruleException $e) {
            echo $e->getMessage() . PHP_EOL;
        }

        $validator = new funcRule(['class' => __CLASS__, 'method' => 'callbackThrow']);
        try {
            $validator->check(false);
            $this->fail();
        } catch (ruleException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }


    public function testDump()
    {
        $rule = new funcRule(['function' =>'is_array']);
        $this->assertArrayHasKey('type', $rule->dump());
        $this->assertEquals('is_array', $rule->dump()['function']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new funcRule([
            'function' =>'is_array',
            'comment' => 'hogehoge',
            'optional' => true
        ]);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('hogehoge', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(true, $rule->dump()['optional']);
    }

}
