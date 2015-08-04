<?php

namespace mihoshi\hashValidator;
require_once __DIR__ . DIRECTORY_SEPARATOR . 'rule' . DIRECTORY_SEPARATOR . 'ruleFactory.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'hashValidatorException.php';

class hashValidator
{
    /** @var  ruleInterface */
    private $rule;

    public function __construct($arg, $type = 'hash')
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'loader' . DIRECTORY_SEPARATOR . $type . 'Loader.php';
        if (!file_exists($file)) {
            throw new hashValidatorException('invalid data type:' . $type);
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

    public function check($arg)
    {
        return $this->rule->check($arg);
    }

    public function toText()
    {
        return $this->rule->toText();
    }
}
