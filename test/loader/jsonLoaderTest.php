<?php

namespace mihoshi\hashValidator;
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class jsonLoaderTest extends hashValidatorTestCase
{
    public function testRead()
    {
        // 実存しないファイル
        $loader = new jsonLoader();
        try {
            $loader->load('testReadJsonXX.yml');
            $this->fail();
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail();
        }

        // json以外のファイル
        $loader = new jsonLoader();
        try {
            $loader->load('../testData/testReadYaml01.yml');
            $this->fail();
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail();
        }

        $this->assertSame(['key' => 'int', 'min' => 0],
            $loader->load(realpath(dirname(__DIR__) . '/testData/testReadJson01.json')));
    }

    public function testInclude()
    {
        $loader = new jsonLoader();
        $def = $loader->load(realpath(dirname(__DIR__) . '/testData/testIncludeJson01.json'));
        var_dump($def);
    }
}
