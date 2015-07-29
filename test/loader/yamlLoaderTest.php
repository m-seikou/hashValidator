<?php

namespace mihoshi\hashValidator;
include_once '../hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));


class yamlLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testRead()
    {
        // �������Ȃ��t�@�C��
        $loader = new yamlLoader();
        try {
            $loader->load('testReadJsonXX.yml');
            $this->fail();
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail();
        }

        // json�ȊO�̃t�@�C��
        $loader = new yamlLoader();
        try {
            $result = $loader->load(__FILE__);
            $this->fail($result);
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail(get_class($e) . PHP_EOL . $e->getMessage() . PHP_EOL.$e->getTraceAsString());
        }

        $this->assertSame(['type' => 'int', 'min' => 0], $loader->load('../testData/testReadYaml01.yml'));
    }

}