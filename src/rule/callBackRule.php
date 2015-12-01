<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/11/30
 * Time: 10:59
 */

namespace mihoshi\hashValidator;
require_once dirname(__DIR__) . '/interface/ruleInterface.php';


class callBackRule implements ruleInterface
{
    private $class;
    private $method;
    public function __construct($rule)
    {
        if(array_key_exists('class',$rule)===false){
            throw new ruleException();
        }
        if(array_key_exists('method',$rule)===false){
            throw new ruleException();
        }

        if(!class_exists($rule['class'])){
            throw new ruleException();
        }

        if(!method_exists($rule['class'],$rule['method'])){
            throw new ruleException();
        }

        $this->class = $rule['class'];
        $this->method = $rule['method'];
    }

    /**
     * @param $value
     * @return mixed バリデーションをかけた値
     * @throws ruleException バリデーションを通過できない場合に発生する例外
     */
    public function check($value)
    {
        static $class = null;
        if(is_null($class)){
            $class = $this->class;
        }
        static $method = null;
        if(is_null($method)){
            $method = $this->method;
        }

        if($class::$method($value) === false){
            throw new ruleException('invalid value:' . var_export($value, true));
        }
        return $value;
    }

    /**
     * hash の必須設定を参照するためのインターフェース
     * @return bool
     */
    public function isOptional()
    {
        return true;
    }

    public function dump()
    {
        return [];
    }
}