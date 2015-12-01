<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/12/01
 * Time: 15:11
 */

namespace mihoshi\hashValidator;
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class callBackRuleTest extends hashValidatorTestCase
{
    public function test_construct(){
        new callBackRule(['class' => '\mihoshi\hashValidator\sampleFunction','method'=>'true']);
        new callBackRule(['class' => '\mihoshi\hashValidator\sampleFunction','method'=>'mirror']);
        foreach([
                    ['class' => 'sampleFunction','method'=>'true'],
                    ['class' => '\mihoshi\hashValidator\sampleFunction','method'=>'hoge'],
                ] as $rule){
            try {
                new callBackRule($rule);
                $this->fail();
            }catch(ruleException $e) {
            }
        }
    }

    public function test_check(){
        $rule = new callBackRule(['class' => '\mihoshi\hashValidator\sampleFunction','method'=>'mirror']);
        $this->assertTrue($rule->check(true));
        try {
            $rule->check(false);
            $this->fail();
        }catch(ruleException $e) {
        }
    }

}

class sampleFunction{
    public static function true(){
        return true;
    }

    public static function false(){
        return false;
    }

    public static function mirror($val){
        return $val;
    }
}