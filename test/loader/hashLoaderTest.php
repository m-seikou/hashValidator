<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/27
 * Time: 10:14
 */

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\loaders\hashLoader;

class hashLoaderTest extends hashValidatorTestCase
{
    public function dataLoad()
    {
        yield "empty" => [[]];
        yield "array" => [[1, 2, 3, 4, 5]];
        yield "hash" => [['a' => 'aa', 'b' => 'bb']];
    }

    /**
     * @dataProvider dataLoad
     */
    public function testLoad(array $data): void
    {
        $validator = new hashLoader();
        self::assertSame($data, $validator->load($data));
    }
}
