<?php

namespace mihoshi\hashValidator\rule;

interface ruleInterface
{

    public function __construct($rule);

    /**
     * @param $value
     * @return mixed バリデーションをかけた値
     * @throws \mihoshi\hashValidator\invalidRuleException バリデーションを通過できない場合に発生する例外
     */
    public function check($value);

    /**
     * hashの必須設定を参照するためのインターフェース
     * @return bool
     */
    public function isOptional();

    public function dump();

    public function getDefault();
}
