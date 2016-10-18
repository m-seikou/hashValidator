<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:48
 */

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

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
