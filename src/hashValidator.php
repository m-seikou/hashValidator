<?php

namespace mihoshi\hashValidator;
require_once __DIR__ . DIRECTORY_SEPARATOR . 'rule/ruleFactory.php';

class hashValidator
{
    /** ’è‹`‚ª‚¨‚©‚µ‚¢Œn */
    const ERR_INVALID_DEFINE = 2;
    /** ’l‚ª‚¨‚©‚µ‚¢Œn */
    const ERR_INVALID_VALUE = 3;

    const DEFINE_ARRAY = 0;
    const DEFINE_YAML_FILE = 1;
    const DEFINE_JSON_FILE = 2;

    /** @var array hash key list for error message */
    private $path = ['$arg'];

    /** @var  loaderInterface */
    private $loader;

    /** @var array include files */
    private $files = [];

    /** @var  ruleInterface */
    private $define;

    public function __construct($arg, $type = self::DEFINE_ARRAY)
    {
        switch ($type) {
            case self::DEFINE_ARRAY:
                require_once __DIR__ . DIRECTORY_SEPARATOR . 'loader' . DIRECTORY_SEPARATOR . 'hashLoader.php';
                $this->loader = new hashLoader();
                break;
            case self::DEFINE_YAML_FILE:
                require_once __DIR__ . DIRECTORY_SEPARATOR . 'loader' . DIRECTORY_SEPARATOR . 'yamlLoader.php';
                $this->loader = new yamlLoader();
                break;
            case self::DEFINE_JSON_FILE:
                require_once __DIR__ . DIRECTORY_SEPARATOR . 'loader' . DIRECTORY_SEPARATOR . 'jsonLoader.php';
                $this->loader = new jsonLoader();
                break;
            default:
                throw new hashValidatorException('invalid data type:' . $type);
        }
        $this->define = ruleFactory::getInstance($this->loader->load($arg));
    }

    // @todo ‚â‚Á‚Ï‚èLoader‚ÉˆÚ‚·‚×‚«‚â‚È
    private function resolveInclude($def)
    {
        switch ($def['type']) {
            case 'include':
                $file = dirname(end($this->files)) . DIRECTORY_SEPARATOR . $def['value'];
                $array = $this->loader->load($file);
                array_push($this->files, $file);
                return $this->resolveInclude($array);
            case 'hash':
                foreach ($def['value'] as $key => &$val) {
                    $val = $this->resolveInclude($val);
                }
                return $def;
            default:
                return $def;
        }
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
