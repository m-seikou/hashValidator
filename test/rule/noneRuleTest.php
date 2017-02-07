<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:48
 */

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\noneRule;

class noneRuleTest extends hashValidatorTestCase
{
    public function dataPass()
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
    public function testPass($data)
    {
        $validator = new noneRule(['rule' => ['type' => 'none']]);
        $this->assertSame($data, $validator->check($data));
    }

    public function testDump()
    {
        $validator = new noneRule([]);
        $this->assertEquals('none', $validator->dump()['type']);
    }

}
