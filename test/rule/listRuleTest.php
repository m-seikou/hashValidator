<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\listRule;

class listRuleTest extends hashValidatorTestCase
{
    public function dataRuleFail()
    {
        yield [['rule' => []]];
        yield [[]];
    }

    /**
     * @param $rule
     * @dataProvider dataRuleFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     */
    public function testRuleFail($rule)
    {
        new listRule($rule);
    }

    public function dataPass()
    {
        yield [['rule' => ['type' => 'int']], [0, 1, 2, 3], [0, 1, 2, 3]];
        yield [['rule' => ['type' => 'int']], ['a' => 0, 1, 2, 3], ['a' => 0, 1, 2, 3]];
        yield [['rule' => ['type' => 'int'], 'min' => 2], [0, 1], [0, 1]];
        yield [['rule' => ['type' => 'int'], 'max' => 3], [0, 1], [0, 1]];
    }

    /**
     * @param $rule
     * @param $data
     * @param $expected
     * @dataProvider dataPass
     */
    public function testPass($rule, $data, $expected)
    {
        $validator = new listRule($rule);
        $this->assertSame($expected, $validator->check($data));
    }

    public function dataFail()
    {
        yield [['rule' => ['type' => 'int']], 'string'];
        yield [['rule' => ['type' => 'int']], new \stdClass()];
        yield [['rule' => ['type' => 'int']], [0, 1, 2, 'a']];
        yield [['rule' => ['type' => 'int'], 'min' => 2], [0]];
        yield [['rule' => ['type' => 'int'], 'max' => 3], [0, 1, 2, 3]];
    }

    /**
     * @param $rule
     * @param $data
     * @dataProvider dataFail
     * @expectedException \mihoshi\hashValidator\exceptions\invalidDataException
     */
    public function testFail($rule, $data)
    {
        $validator = new listRule($rule);
        $validator->check($data);
    }

    public function testDump()
    {
        $validator = new listRule(['rule' => ['type' => 'int']]);
        $this->assertArrayNotHasKey('max', $validator->dump());
        $this->assertArrayNotHasKey('min', $validator->dump());
        $this->assertArrayHasKey('rule', $validator->dump());

    }

    public function testDumpWithMaxMin()
    {
        $dump = (new listRule(['rule' => ['type' => 'int'], 'max' => 3, 'min' => 1]))->dump();
        $this->assertArrayHasKey('max', $dump);
        $this->assertEquals(3, $dump['max']);
        $this->assertArrayHasKey('min', $dump);
        $this->assertEquals(1, $dump['min']);
        $this->assertArrayHasKey('rule', $dump);
    }
}
