<?php

namespace mihoshi\hashValidator\interfaces;

use mihoshi\hashValidator\exceptions\invalidDataException;
use mihoshi\hashValidator\exceptions\invalidRuleException;
use Closure;

interface ruleInterface
{

    /**
     * ruleInterface constructor.
     * @param array $rule
     * @throws invalidRuleException $ruleに不備があった場合、この例外をthrowすること
     */
    public function __construct(array $rule);

    /**
     * @param $value
     * @return mixed validatorは入力値 $valueをこの値に置き換えられます
     * @throws invalidDataException $valueがパスしない値の場合、この例外をthroeすること
     */
    public function check($value);

    /**
     * hashの必須設定を参照するためのインターフェース
     * @return bool
     */
    public function isOptional():bool;

    /**
     * hashのデフォルト値を参照するためのインターフェース
     * @return mixed
     */
    public function getDefault();

    /**
     * ルールに関する各種パラメーターを連想配列で返す
     * @param Closure|null $closure
     * @return array
     */
    public function dump(?Closure $closure = null):array;

}
