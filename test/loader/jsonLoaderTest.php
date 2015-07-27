<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/27
 * Time: 10:14
 */

namespace mihoshi\hashValidator;
include_once '../hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

class jsonLoaderTest extends hashValidatorTestCase
{
    public function testReadJson()
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
            $result = $loader->load('../testData/testReadYaml01.yml');
            var_dump($result);
            $this->fail();
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\exception $e) {
            $this->fail();
        }

        $this->assertSame(['key' => 'int', 'min' => 0], $loader->load('../testData/testReadJson01.json'));
    }
}
