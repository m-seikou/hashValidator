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
    public function testLoad()
    {
        $validator = new hashLoader();
        $case = [
            [],
            [1, 2, 3, 4, 5],
            ['a' => 'aa', 'b' => 'bb'],
        ];
        foreach ($case as $array) {
            $this->assertSame($array, $validator->load($array));
        }
    }
}
