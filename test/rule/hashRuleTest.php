<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\hashRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

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
        } catch (invalidDataException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        try {
            $validator->check(['hoge' => 'aa']);
            $this->fail();
        } catch (invalidDataException $e) {
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
        } catch (invalidDataException $e) {
            echo $e->getMessage() . PHP_EOL;
        }

    }

    public function testDump()
    {
    }

}
