<?php

namespace mihoshi\hashValidator;

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
     * @throws exceptions\invalidRuleException
     */
    public function __construct($arg, $type = 'hash')
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . $type . 'Loader.php';
        if (!file_exists($file)) {
            throw new exceptions\invalidRuleException('invalid data type:' . $type);
        }
        require_once $file;
        $class = __NAMESPACE__ . '\\' . $type . 'Loader';
        /** @var interfaces\loaderInterface $loader */
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
