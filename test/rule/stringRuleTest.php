<?php

namespace mihoshi\hashValidator;
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class stringRuleTest extends hashValidatorTestCase
{
    public function testType()
    {
        $validator = new stringRule([]);
        foreach (['', 'hogehogehogehoge'] as $data) {
            $this->assertEquals($data, $validator->check($data));
        }
        foreach ([[], new \stdClass()] as $data) {
            try {
                $validator->check($data);
                $this->fail();
            } catch (ruleException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function testLength()
    {

        $validator = new stringRule(['max' => 5, 'min' => 2]);
        foreach (['22', '333', '55555'] as $data) {
            $this->assertEquals($data, $validator->check($data), $data);
        }
        foreach (['2', '666666'] as $data) {
            try {
                $validator->check($data);
                $this->fail();
            } catch (ruleException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function testPreg()
    {

        $validator = new stringRule(['preg' => '/hogehoge/']);
        foreach (['hogehoge', 'aaaahogehogeffuuuu', 'aaaaaaaaaahogehoge'] as $data) {
            $this->assertEquals($data, $validator->check($data), $data);
        }
        foreach (['hogeahoge'] as $data) {
            try {
                $validator->check($data);
                $this->fail($data);
            } catch (ruleException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function testDump()
    {
        $rule = new stringRule([]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertNull($rule->dump()['max']);
        $this->assertNull($rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new stringRule([
            'max' => 100,
            'min' => 10,
            'comment' => 'hogehoge',
            'optional' => true
        ]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertEquals(100, $rule->dump()['max']);
        $this->assertArrayHasKey('min', $rule->dump());
        $this->assertEquals(10, $rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('hogehoge', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(true, $rule->dump()['optional']);
    }

}
