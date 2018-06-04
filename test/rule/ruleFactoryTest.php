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
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'testData' . DIRECTORY_SEPARATOR . 'fooRule.php';
        $result = ruleFactory::getInstance(['type' => \testData\fooRule::class]);
        $this->assertInstanceOf(\testData\fooRule::class, $result);
    }

    /**
     * @expectedException \mihoshi\hashValidator\exceptions\invalidRuleException
     * @expectedExceptionCode 999
     */
    public function testRuleThrowException(){
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'testData' . DIRECTORY_SEPARATOR . 'exception.php';
        $result = ruleFactory::getInstance(['type' => \testData\exception::class]);
    }
}
