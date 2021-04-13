<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\loaders\jsonLoader;
use mihoshi\hashValidator\exceptions\loaderException;

class jsonLoaderTest extends hashValidatorTestCase
{
    public function testLoad_notfound(): void
    {
        $this->expectException(loaderException::class);
        $this->expectExceptionCode(loaderException::ERR_FILE_NOT_READ);
        // 実存しないファイル
        $loader = new jsonLoader();
        $loader->load('testReadJsonXX.yml');
    }

    public function testLoad_notJson(): void
    {
        $this->expectException(loaderException::class);
        $this->expectExceptionCode(loaderException::ERR_FILE_NOT_READ);
        // 実存しないファイル
        $loader = new jsonLoader();
        $loader->load('../testData/testReadYaml01.yml');
    }

    public function testRead(): void
    {
        $loader = new jsonLoader();
        self::assertSame(['key' => 'int', 'min' => 0],
            $loader->load(realpath(dirname(__DIR__) . '/testData/testReadJson01.json')));
    }

    public function testInclude(): void
    {
        $loader = new jsonLoader();
        $def = $loader->load(realpath(dirname(__DIR__) . '/testData/testIncludeJson01.json'));
        self::assertSame(["type" => "hash", "key" => ["key1" => ["type" => "int", "min" => 0,]]], $def);
    }
}
