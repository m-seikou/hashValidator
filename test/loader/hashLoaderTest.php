<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/27
 * Time: 10:14
 */

namespace mihoshi\hashValidator;

include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'hashValidatorTestCase.php';
include_once str_replace(TEST_ROOT, SRC_ROOT, __DIR__) . '/' . str_replace('Test.php', '.php', basename(__FILE__));

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
