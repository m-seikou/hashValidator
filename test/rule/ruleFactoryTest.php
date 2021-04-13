<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\intRule;
use mihoshi\hashValidator\rule\ruleFactory;
use mihoshi\hashValidator\exceptions\invalidRuleException;

class ruleFactoryTest extends hashValidatorTestCase
{

    public function testFactoryEnableType():void
    {
        self::assertInstanceOf(intRule::class, ruleFactory::getInstance(['type' => 'int']));
    }

    /**
     */
    public function testFactoryIgnoreType():void
    {
        $this->expectException(invalidRuleException::class);
        ruleFactory::getInstance(['type' => 'integer']);
    }

    public function testAdditionalRule():void
    {
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'testData' . DIRECTORY_SEPARATOR . 'fooRule.php';
        $result = ruleFactory::getInstance(['type' => \testData\fooRule::class]);
        self::assertInstanceOf(\testData\fooRule::class, $result);
    }

    /**
     */
    public function testRuleThrowException():void
    {
        $this->expectException(invalidRuleException::class);
        $this->expectExceptionCode(999);
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'testData' . DIRECTORY_SEPARATOR . 'exception.php';
        $result = ruleFactory::getInstance(['type' => \testData\exception::class]);
    }
}
