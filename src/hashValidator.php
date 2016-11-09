<?php

namespace mihoshi\hashValidator;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'rule/ruleFactory.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'invalidDataException.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'invalidRuleException.php';

/**
 * Class hashValidator
 * @package mihoshi\hashValidator
 */
class hashValidator
{
    /** @var  ruleInterface */
    private $rule;

    /**
     * hashValidator constructor.
     * @param array|String $arg ルール配列 or ルールファイルのパス
     * @param string $type [hash|yaml|json] $arg 種類
     * @throws invalidRuleException
     */
    public function __construct($arg, $type = 'hash')
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'loader' . DIRECTORY_SEPARATOR . $type . 'Loader.php';
        if (!file_exists($file)) {
            throw new invalidRuleException('invalid data type:' . $type);
        }
        require_once $file;
        $class = __NAMESPACE__ . '\\' . $type . 'Loader';
        /** @var loaderInterface $loader */
        $loader = new $class();
        $this->rule = ruleFactory::getInstance($loader->load($arg));
    }

    public function dump()
    {
        return $this->rule->dump();
    }

    /**
     * @param array $arg
     * @return array
     * @throw invalidDataException 入力エラーがあった際にthrowするexception
     */
    public function check($arg)
    {
        return $this->rule->check($arg);
    }

    public function toText()
    {
        return $this->rule->toText();
    }
}
