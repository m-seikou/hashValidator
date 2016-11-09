<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:02
 */

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class boolRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testIntValidation()
    {
        $validator = new boolRule([]);
        foreach ([true, false] as $data) {
            $this->assertEquals($data, $validator->check($data));
        }
        foreach (['a', [], new \stdClass(), 0, 1, null] as $data) {
            try {
                $validator->check($data);
                $this->fail();
            } catch (invalidDataException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }

        $validator = new boolRule(['arrow_null' => true]);
        $this->assertNull($validator->check(null));
    }

}
