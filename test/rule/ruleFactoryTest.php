<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\rule\intRule;
use mihoshi\hashValidator\rule\ruleFactory;
use mihoshi\hashValidator\exceptions\invalidRuleException;

class ruleFactoryTest extends hashValidatorTestCase
{

	public function testFactory()
	{
		$this->assertInstanceOf(intRule::class, ruleFactory::getInstance(['type' => 'int']));
		try {
			ruleFactory::getInstance(['type' => 'integer']);
			$this->fail();
		} catch (invalidRuleException $e) {
			echo $e->getMessage() . PHP_EOL;
		}
	}

	public function testAdditionalRule()
	{
		ruleFactory::addRuleDir(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'testData', '\\testData');
		$result = ruleFactory::getInstance(['type' => 'foo']);
		$this->assertInstanceOf(\testData\fooRule::class, $result);
	}
}
