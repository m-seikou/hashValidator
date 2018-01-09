<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:48
 */

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\noneRule;

class noneRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testIntValidation()
    {
        $validator = new noneRule(['rule' => ['type' => 'none']]);
        foreach([] as $value){
            $this->assertSame($value , $validator->check($value));
        }
    }

    public function testDump()
    {
        $validator = new noneRule([]);
        $this->assertEquals( 'none',$validator->dump()['type']);
    }

}
