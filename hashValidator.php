<?php

namespace mihoshi\hashValidator;

class hashValidator
{
    const DEFINE_ARRAY = 0;
    const DEFINE_YAML_FILE = 1;
    const DEFINE_JSON_FILE = 2;

    /** ファイルがないとか読めないとか */
    const ERR_FILE_NOT_READ = 1;
    /** 定義がおかしい系 */
    const ERR_INVALID_DEFINE = 2;
    /** 値がおかしい系 */
    const ERR_INVALID_VALUE = 3;

    /** @var  array validation rule */
    private $define;

    public function __construct($arg, $type = self::DEFINE_ARRAY)
    {
        switch ($type) {
            case self::DEFINE_ARRAY:
                if (!is_array($arg)) {
                    throw new hashValidatorException('not array', self::ERR_FILE_NOT_READ);
                }
                $this->define = $arg;
                break;
            case self::DEFINE_YAML_FILE:
                try {
                    $this->define = yaml_parse_file($arg);
                } catch (\Exception $e) {
                    throw new hashValidatorException($e->getMessage(), self::ERR_FILE_NOT_READ, $e);
                }
                break;
            case self::DEFINE_JSON_FILE:
                try {
                    $this->define = json_decode(file_get_contents($arg), true);
                } catch (\Exception $e) {
                    throw new hashValidatorException($e->getMessage(), self::ERR_FILE_NOT_READ, $e);
                }
                break;
            default:
                throw new hashValidatorException('invalid data type:' . $type, self::ERR_FILE_NOT_READ);
        }
    }

    public function getDefine()
    {
        return $this->define;
    }

    public function validate($arg)
    {
        return $this->_validate($arg, $this->define);
    }

    private function _validate($arg, $def)
    {
        if(!isset($def['type'])){
            throw new hashValidatorException('invalid define',self::ERR_INVALID_DEFINE);
        }
        switch($def['type']){
            case 'int':
                return $this->_validateInt($arg,$def);
        }
    }

    private function _validateInt($arg,$def){
        if(!is_numeric($arg)){
            throw new hashValidatorException('invalid int value:'. var_export($arg),self::ERR_INVALID_VALUE);
        }
        $val = (int)$arg;
        if (isset($def['min']) && $val < $def['min']) {
            throw new hashValidatorException('input:' . $val . ' less than ' . $def['min'], self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $val > $def['max']) {
            throw new hashValidatorException('input:' . $val . ' grater than ' . $def['min'], self::ERR_INVALID_VALUE);
        }
        return (int)$val;
    }
}

class hashValidatorException extends \Exception
{
}