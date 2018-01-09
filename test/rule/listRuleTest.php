<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\listRule;
use mihoshi\hashValidator\exceptions\invalidDataException;

class listRuleTest extends hashValidatorTestCase
{
    public function testIntValidation()
    {
        $validator = new listRule(['rule' => ['type' => 'int']]);
        $this->assertSame([0, 1, 2, 3], $validator->check([0, 1, 2, 3]));
        $this->assertSame(['a' => 0, 1, 2, 3], $validator->check(['a' => 0, 1, 2, 3]));
        try {
            $validator->check([0, 1, 2, 'a']);
            $this->fail();
        } catch (invalidDataException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        $validator = new listRule(['rule' => ['type' => 'int'], 'min' => 2]);
        $this->assertSame([0, 1], $validator->check([0, 1]));
        try {
            $validator->check([0]);
            $this->fail();
        } catch (invalidDataException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        $validator = new listRule(['rule' => ['type' => 'int'], 'max' => 3]);
        $this->assertSame([0, 1], $validator->check([0, 1]));
        try {
            $validator->check([0, 1, 2, 3]);
            $this->fail();
        } catch (invalidDataException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    public function testDump()
    {
        $validator = new listRule(['rule' => ['type' => 'int']]);
        $this->assertArrayNotHasKey('max', $validator->dump());
        $this->assertArrayNotHasKey('min', $validator->dump());
        $this->assertArrayHasKey('rule', $validator->dump());

    }

}
