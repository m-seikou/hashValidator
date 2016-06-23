<?php

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class hashRuleTest extends hashValidatorTestCase
{
    public function testHashValidation()
    {
        $validator = new hashRule(['key' => ['hoge' => ['type' => 'int']]]);
        $data = ['hoge' => 10];
        $this->assertSame($data, $validator->check($data));

        try {
            $validator->check(['fuga' => 1]);
            $this->fail();
        } catch (ruleException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        try {
            $validator->check(['hoge' => 'aa']);
            $this->fail();
        } catch (ruleException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        $validator = new hashRule(['key' => ['hoge' => ['type' => 'int', 'optional' => true]]]);
        $this->assertSame([], $validator->check(['fuga' => 1]));

        $validator = new hashRule(
            [
                'key' => [
                    'hoge' => [
                        'type' => 'hash',
                        'key'  => [
                            'fuga' => [
                                'type' => 'int',
                            ],
                        ],
                    ],
                ],
            ]
        );
        $this->assertSame(['hoge' => ['fuga' => 1]], $validator->check(['hoge' => ['fuga' => 1]]));
        try {
            $validator->check(['hoge' => ['fuga' => 'bb']]);
            $this->fail();
        } catch (ruleException $e) {
            echo $e->getMessage() . PHP_EOL;
        }

    }

    public function testDump()
    {
    }

}
