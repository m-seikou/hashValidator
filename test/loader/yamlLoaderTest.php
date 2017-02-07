<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\loaders\yamlLoader;
use mihoshi\hashValidator\exceptions\loaderException;

class yamlLoaderTest extends hashValidatorTestCase
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
        } catch (\Exception $e) {
            $this->fail();
        }

        // yaml以外のファイル
        $loader = new yamlLoader();
        try {
            $result = $loader->load(__FILE__);
            $this->fail(var_export($result, true));
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\Exception $e) {
            $this->fail(get_class($e) . PHP_EOL . $e->getMessage() . PHP_EOL . $e->getTraceAsString());
        }

        $this->assertSame(['type' => 'int', 'min' => 0],
            $loader->load(dirname(__DIR__) . '/testData/testReadYaml01.yml'));
    }

    public function testInclude()
    {
        $loader = new yamlLoader();
        $def = $loader->load(realpath(dirname(__DIR__) . '/testData/testIncludeYaml01.yml'));
        $this->assertSame([
            "type" => "hash",
            "key"  => [
                "key1" => ["type" => "int", "min" => 0,],
                'key2' => ['type' => 'int'],
            ],
        ], $def);
    }

}
