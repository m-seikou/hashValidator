<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:48
 */

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\rule\noneRule;

class noneRuleTest extends hashValidatorTestCase
{
    public function dataPass(): Generator
    {
        yield [1];
        yield [12.345];
        yield ['any string'];
        yield [[]];
        yield [new \stdClass()];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPass($data): void
    {
        $validator = new noneRule(['rule' => ['type' => 'none']]);
        self::assertSame($data, $validator->check($data));
    }

    public function testDump(): void
    {
        $validator = new noneRule([]);
        self::assertEquals('none', $validator->dump()['type']);
    }

}
