<?php

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class ruleFactoryTest extends hashValidatorTestCase
{

    public function testFactory()
    {
        $this->assertInstanceOf(__NAMESPACE__ . '\\' . 'intRule', ruleFactory::getInstance(['type' => 'int']));
        try {
            ruleFactory::getInstance(['type' => 'integer']);
            $this->fail();
        } catch (ruleException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
