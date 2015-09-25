<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/16
 * Time: 10:37
 */

namespace mihoshi\hashValidator;

include_once 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));


class hashValidatorTest extends hashValidatorTestCase
{

    public function testValidate()
    {
        $validator =
            new hashValidator(['type' => 'hash', 'key' => ['hoge' => ['type' => 'int']]], 'hash');
        $validator->check(['hoge' => 10]);
    }

    public function testGetDefine()
    {
        $validator =
            new hashValidator(['type' => 'hash', 'key' => ['hoge' => ['type' => 'int']]], 'hash');
        $def = $validator->dump();
        $this->assertArrayHasKey('type', $def);
        $this->assertArrayHasKey('key', $def);
        $this->assertArrayHasKey('hoge', $def['key']);
        $this->assertArrayHasKey('type', $def['key']['hoge']);
    }

}