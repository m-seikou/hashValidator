<?php

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;
use mihoshi\hashValidator\rule\listRule;
use stdClass;

class listRuleTest extends hashValidatorTestCase
{
    public function dataRuleFail(): Generator
    {
        yield [['rule' => []]];
        yield [[]];
    }

    /**
     * @param $rule
     * @dataProvider dataRuleFail
     */
    public function testRuleFail($rule): void
    {
        $this->expectException(invalidRuleException::class);
        new listRule($rule);
    }

    public function dataPass(): Generator
    {
        yield [['rule' => ['type' => 'int']], [0, 1, 2, 3, 3], [0, 1, 2, 3, 3]];
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
    public function testPass($rule, $data, $expected): void
    {
        $validator = new listRule($rule);
        self::assertSame($expected, $validator->check($data));
    }

    public function dataFail(): Generator
    {
        yield [['rule' => ['type' => 'int']], 'string'];
        yield [['rule' => ['type' => 'int']], new stdClass()];
        yield [['rule' => ['type' => 'int']], [0, 1, 2, 'a']];
        yield [['rule' => ['type' => 'int'], 'min' => 2], [0]];
        yield [['rule' => ['type' => 'int'], 'max' => 3], [0, 1, 2, 3]];
    }

    /**
     * @param $rule
     * @param $data
     * @dataProvider dataFail
     */
    public function testFail($rule, $data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new listRule($rule);
        $validator->check($data);
    }

    public function testDump(): void
    {
        $validator = new listRule(['rule' => ['type' => 'int']]);
        self::assertArrayNotHasKey('max', $validator->dump());
        self::assertArrayNotHasKey('min', $validator->dump());
        self::assertArrayHasKey('rule', $validator->dump());

    }

    public function testDumpWithMaxMin(): void
    {
        $dump = (new listRule(['rule' => ['type' => 'int'], 'max' => 3, 'min' => 1]))->dump();
        self::assertArrayHasKey('min', $dump);
        self::assertArrayHasKey('max', $dump);
        self::assertEquals(3, $dump['max']);
        self::assertEquals(1, $dump['min']);
        self::assertArrayHasKey('rule', $dump);
    }

    /**
     */
    public function testUniqFail(): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new listRule(['rule' => ['type' => 'int'], 'unique' => true]);
        $validator->check([1, 1]);
    }
}
