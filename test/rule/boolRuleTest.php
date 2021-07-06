<?php
/**
 * Created by PhpStorm.
 * User: 745
 * Date: 2016/06/29
 * Time: 14:02
 */

namespace mihoshi\hashValidator;

use Generator;
use mihoshi\hashValidator\rule\boolRule;
use mihoshi\hashValidator\exceptions\invalidDataException;
use stdClass;

class boolRuleTest extends hashValidatorTestCase
{
    public function dataPass(): Generator
    {
        yield [true];
        yield [false];
    }

    /**
     * @param $data
     * @dataProvider dataPass
     */
    public function testPassStrict($data): void
    {
        $validator = new boolRule(['message'=>'error message']);
        self::assertEquals($data, $validator->check($data));
    }

    public function dataFail(): Generator
    {
        yield ['a'];
        yield [[]];
        yield [new stdClass()];
        yield [0];
        yield [1];
        yield [null];
    }

    /**
     * @param $data
     * @dataProvider dataFail
     */
    public function testFailStrict($data): void
    {
        $this->expectException(invalidDataException::class);
        $validator = new boolRule(['message' => 'error message']);
        $validator->check($data);
    }

    public function testArrowNull(): void
    {
        $validator = new boolRule(['arrow_null' => true]);
        self::assertNull($validator->check(null));
    }

    /**
     * @param $data
     * @dataProvider  dataFail
     */
    public function testFail($data): void
    {
        $validator = new boolRule(['strict'=>false]);
        $result = $validator->check($data);
        self::assertContains($result,[true,false]);
    }

    public function testDump(): void
    {
        $validator = new boolRule([]);
        self::assertEquals('bool',$validator->dump()['type']);

    }
}
