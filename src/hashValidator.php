<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\exceptions\loaderException;
use Closure;
/**
 * Class hashValidator
 * @package mihoshi\hashValidator
 */
class hashValidator
{
    /** @var  interfaces\ruleInterface */
    private $rule;

    /**
     * hashValidator constructor.
     * @param array|String $arg ルール配列 or ルールファイルのパス
     * @param string $type [hash|yaml|json] $arg 種類
     * @param array $namespace
     * @throws exceptions\loaderException
     */
    public function __construct($arg, string $type = 'hash', array $namespace = [])
    {
        $class = class_exists($type) ? $type : __NAMESPACE__ . '\\loaders\\' . $type . 'Loader';
        /** @var Interfaces\loaderInterface $loader */
        $loader = new $class();
        try {
            $this->rule = rule\ruleFactory::getInstance($loader->load($arg));
        } catch (\Exception $e) {
            throw new loaderException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param ?Closure $dump
     * @return array
     */
    public function dump(?Closure $dump = null): array
    {
        return $this->rule->dump($dump);
    }

    /**
     * @param array $arg
     * @return array
     * @throw exceptions\invalidDataException 入力エラーがあった際にthrowするexception
     */
    public function check($arg)
    {
        return $this->rule->check($arg);
    }

    /**
     * @return string
     */
    public function toText()
    {
        return $this->rule->toText();
    }
}
