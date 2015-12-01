<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/11/30
 * Time: 11:10
 */

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/interface/ruleInterface.php';

abstract class abstractRule implements ruleInterface
{
    protected $comment = '';
    protected $optional = false;

    public function __construct($rule)
    {
        if (isset($rule['comment'])) {
            $this->comment = $rule['comment'];
        }
        if (isset($rule['optional'])) {
            $this->optional = $rule['optional'];
        }
    }

    /**
     * hash の必須設定を参照するためのインターフェース
     * @return bool
     */
    public function isOptional()
    {
        return $this->optional;
    }

    public function dump()
    {
        return [
            'comment' => $this->comment,
            'optional' => $this->optional
        ];
    }

}