<?php

namespace mihoshi\hashValidator;
require_once __DIR__ . DIRECTORY_SEPARATOR . 'rule/ruleFactory.php';

class hashValidator
{
    /** ’è‹`‚ª‚¨‚©‚µ‚¢Œn */
    const ERR_INVALID_DEFINE = 2;
    /** ’l‚ª‚¨‚©‚µ‚¢Œn */
    const ERR_INVALID_VALUE = 3;

    const DEFINE_ARRAY = 'hash';
    const DEFINE_YAML_FILE = 'yaml';
    const DEFINE_JSON_FILE = 'json';

    /** @var  loaderInterface */
    private $loader;

    /** @var array include files */
    private $files = [];

    /** @var  ruleInterface */
    private $define;

    public function __construct($arg, $type = self::DEFINE_ARRAY)
    {
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'loader' . DIRECTORY_SEPARATOR . $type . 'Loader.php';
        if (!file_exists($file)) {
            throw new hashValidatorException('invalid data type:' . $type);
        }
        require_once $file;
        $class = __NAMESPACE__ . '\\' . $type . 'Loader';
        $this->loader = new $class();
        $this->define = ruleFactory::getInstance($this->loader->load($arg));
    }

    public function getDefine()
    {
        return $this->define->dump();
    }


    public function validate($arg)
    {
        return $this->define->check($arg);
    }

}

class hashValidatorException extends \Exception
{
}
