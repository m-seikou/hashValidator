<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\enumRule;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;

class enumRuleTest extends hashValidatorTestCase
{
    public function testDefine()
    {
        foreach ([[], new \stdClass()] as $value) {
            try {
                new enumRule(['value' => $value]);
                $this->fail();
            } catch (invalidRuleException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function testValue()
    {


        $validator = new enumRule(['value' => [2, 4, 6, 8]]);
        foreach ([2, 4, 6, 8] as $data) {
            $this->assertEquals($data, $validator->check($data), $data);
        }
        foreach ([1, 3, 5, 7, 9] as $data) {
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
        $rule = new enumRule(['value' => [1]]);
        $this->assertArrayHasKey('type', $rule->dump());
        $this->assertEquals('enum', $rule->dump()['type']);
        $this->assertArrayHasKey('value', $rule->dump());
        $this->assertEquals([1], $rule->dump()['value']);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(false, $rule->dump()['optional']);

        $rule = new enumRule([
            'value'    => [1],
            'comment'  => 'hogehoge',
            'optional' => true,
        ]);
        $this->assertArrayHasKey('comment', $rule->dump());
        $this->assertEquals('hogehoge', $rule->dump()['comment']);
        $this->assertArrayHasKey('optional', $rule->dump());
        $this->assertEquals(true, $rule->dump()['optional']);
    }

    public function testToText()
    {
        $rule = new enumRule(['value' => [1]]);
        echo $rule->toText('    ', 1);
        $this->assertStringStartsWith(' ', $rule->toText('    ', 1));
    }
}
