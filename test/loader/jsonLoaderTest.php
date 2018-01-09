<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\loaders\jsonLoader;
use mihoshi\hashValidator\exceptions\loaderException;

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
        } catch (\Exception $e) {
            $this->fail();
        }

        // json以外のファイル
        $loader = new jsonLoader();
        try {
            $loader->load('../testData/testReadYaml01.yml');
            $this->fail();
        } catch (loaderException $e) {
            $this->assertEquals(loaderException::ERR_FILE_NOT_READ, $e->getCode());
        } catch (\Exception $e) {
            $this->fail();
        }

        $this->assertSame(['key' => 'int', 'min' => 0],
            $loader->load(realpath(dirname(__DIR__) . '/testData/testReadJson01.json')));
    }

    public function testInclude()
    {
        $loader = new jsonLoader();
        $def = $loader->load(realpath(dirname(__DIR__) . '/testData/testIncludeJson01.json'));
        $this->assertSame(["type" => "hash", "key" => ["key1" => ["type" => "int", "min" => 0,]]], $def);
    }
}
