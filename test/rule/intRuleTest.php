<?php

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class intRuleTest extends hashValidatorTestCase
{
    public function testIntValidation()
    {
        $validator = new intRule([]);
        foreach ([-PHP_INT_MAX, 0, PHP_INT_MAX, '12345',] as $data) {
            $this->assertEquals($data, $validator->check($data));
        }
        foreach (['a', [], new \stdClass()] as $data) {
            try {
                $validator->check($data);
                $this->fail();
            } catch (ruleException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }

        $validator = new intRule(['max' => 10, 'min' => 2]);
        foreach ([2, 3, 9, 10] as $data) {
            $this->assertEquals($data, $validator->check($data));
        }
        foreach ([0, 1, 11, 12] as $data) {
            try {
                $validator->check($data);
                $this->fail();
            } catch (ruleException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function testDump()
    {
        $rule = new intRule([]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertEquals(PHP_INT_MAX, $rule->dump()['max']);
        $this->assertArrayHasKey('min', $rule->dump());
        $this->assertEquals(-PHP_INT_MAX, $rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new intRule([
            'max'      => 100,
            'min'      => 10,
            'comment'  => 'hogehoge',
            'optional' => true,
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
