<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:02
 */

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\boolRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

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
