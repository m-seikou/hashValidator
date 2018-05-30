<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\intRule;
use mihoshi\hashValidator\rule\ruleFactory;
use mihoshi\hashValidator\exceptions\invalidRuleException;

class ruleFactoryTest extends hashValidatorTestCase
{

    public function testFactoryEnableType()
    {
        $this->assertInstanceOf(intRule::class, ruleFactory::getInstance(['type' => 'int']));
    }

    /**
     * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     */
    public function testFactoryIgnoreType()
    {
        ruleFactory::getInstance(['type' => 'integer']);
    }

    public function testAdditionalRule()
    {
        ruleFactory::addRuleDir(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'testData', '\\testData');
        $result = ruleFactory::getInstance(['type' => 'foo']);
        $this->assertInstanceOf(\testData\fooRule::class, $result);
    }
}
