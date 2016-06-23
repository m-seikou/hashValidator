<?php

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));


class yamlLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testRead()
    {
        // 実存しないファイル
        $loader = new yamlLoader();
        try {
            $loader->load('testReadJsonXX.yml');
            $this->fail();
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail();
        }

        // yaml以外のファイル
        $loader = new yamlLoader();
        try {
            $result = $loader->load(__FILE__);
            $this->fail(var_export($result, true));
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail(get_class($e) . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        $this->assertSame(['type' => 'int', 'min' => 0],
            $loader->load(dirname(__DIR__) . '/testData/testReadYaml01.yml'));
    }

    public function testInclude()
    {
        $loader = new yamlLoader();
        $def = $loader->load(realpath(dirname(__DIR__) . '/testData/testIncludeYaml01.yml'));
        $this->assertSame(["type" => "hash", "key" => ["key1" => ["type" => "int", "min" => 0,]]], $def);
    }

}
