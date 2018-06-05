<?php

namespace mihoshi\hashValidator;

use mihoshi\hashValidator\exceptions\loaderException;

require_once '../vendor/autoload.php';

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
     * @throws exceptions\loaderException
     */
    public function __construct($arg, $type = 'hash')
    {
        $class = __NAMESPACE__ . '\\loader\\' . $type . 'Loader';
        /** @var Interfaces\loaderInterface $loader */
        $loader = new $class();
        $this->rule = rule\ruleFactory::getInstance($loader->load($arg));
    }

    public function dump()
    {
        return $this->rule->dump();
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
