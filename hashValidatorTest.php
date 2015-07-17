<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/16
 * Time: 10:37
 */

namespace mihoshi\hashValidator;

require_once 'hashValidator.php';


class hashValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testReadYaml()
    {
        // 実存しないファイル
        try {
            new hashValidator('testData/testReadYamlXX.yml', hashValidator::DEFINE_YAML_FILE);
            $this->fail();
        } catch (hashValidatorException $e) {
            $this->assertEquals(hashValidator::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail();
        }
    }

    public function testReadJson()
    {
        // 実存しないファイル
        try {
            new hashValidator('testData/testReadJsonXX.yml', hashValidator::DEFINE_JSON_FILE);
            $this->fail();
        } catch (hashValidatorException $e) {
            $this->assertEquals(hashValidator::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail();
        }
    }

    public function testGetDefine()
    {
        $validator = new hashValidator(['type' => 'hash', 'value' => []], hashValidator::DEFINE_ARRAY);
        $this->assertEquals(['type' => 'hash', 'value' => []], $validator->getDefine());
    }

    public function testIntValidation()
    {
        $validator = new hashValidator(['type' => 'int'], hashValidator::DEFINE_ARRAY);
        foreach ([-PHP_INT_MAX,0,PHP_INT_MAX, '12345',] as $data) {
            $this->assertEquals($data, $validator->validate($data));
        }
        foreach (['a',[],new \stdClass()] as $data) {
            try {
                $validator->validate($data);
                $this->fail($data . ' is int?');
            } catch (hashValidatorException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }
}