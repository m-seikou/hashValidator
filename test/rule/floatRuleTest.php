<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\floatRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

class floatRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testIntValidation()
    {
        $validator = new floatRule([]);
        foreach ([-PHP_INT_MAX, 0, PHP_INT_MAX, '12345',] as $data) {
            $this->assertEquals($data, $validator->check($data));
        }
        foreach (['a', [], new \stdClass()] as $data) {
            try {
                $validator->check($data);
                $this->fail();
            } catch (invalidDataException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }

        $validator = new floatRule(['max' => 3.141, 'min' => 2.828]);
        foreach ([2.828, 3, 3.141,] as $data) {
            $this->assertEquals($data, $validator->check($data));
        }
        foreach ([2.827999999, 3.1410001] as $data) {
            try {
                $validator->check($data);
                $this->fail();
            } catch (invalidDataException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function testDump()
    {
        $rule = new floatRule([]);
        $this->assertArrayHasKey('max', $rule->dump());
        $this->assertNull($rule->dump()['max']);
        $this->assertNull($rule->dump()['min']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new floatRule([
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
