<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/16
 * Time: 10:37
 */

namespace mihoshi\hashValidator;

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
        $validator = new hashValidator(['type' => 'hash', 'key' => ['hoge' => ['type' => 'int']]], 'hash');
        $def = $validator->dump();
        $this->assertArrayHasKey('type', $def);
        $this->assertArrayHasKey('key', $def);
        $this->assertArrayHasKey('hoge', $def['key']);
        $this->assertArrayHasKey('type', $def['key']['hoge']);
    }

    /**
     * @expectedException \mihoshi\hashValidator\invalidRuleException
     * @expectedExceptionMessage [aaa][hoge]rule not found:noRule
     */
    public function testInvalidRule()
    {
        new hashValidator([
            'type' => 'hash',
            'key' => [
                'aaa' => [
                    'type' => 'hash',
                    'key' => [
                        'hoge' => ['type' => 'noRule'] // ここでエラーになる
                    ]
                ],
            ]
        ], 'hash');
    }
}
