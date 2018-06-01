<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\enumRule;

class enumRuleTest extends hashValidatorTestCase
{
    public function dataDefine()
    {
        yield '空定義(配列)' => [[]];
        yield '空定義(stdclass)' => [new \stdClass()];
    }

    /**
     * @dataProvider dataDefine
     * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     */
    public function testDefine($value)
    {
        new enumRule(['value' => $value]);
    }

    public function dataPass()
    {
        yield [2];
        yield [4];
        yield [6];
        yield [8];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPass($data)
    {
        $validator = new enumRule(['value' => [2, 4, 6, 8]]);
        $this->assertEquals($data, $validator->check($data), $data);
    }

    public function dataFail()
    {
        yield [1];
        yield [3];
        yield [5];
        yield [7];
        yield [9];
        yield [[]];
        yield [new \stdClass()];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testFail($data)
    {
        $validator = new enumRule(['value' => [2, 4, 6, 8]]);
        $validator->check($data);
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
            'value' => [1],
            'comment' => 'hogehoge',
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
        $this->assertStringStartsWith(' ', $rule->toText('    ', 1));
    }
}
