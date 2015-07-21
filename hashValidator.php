<?php

namespace mihoshi\hashValidator;

class hashValidator
{
    const DEFINE_ARRAY = 0;
    const DEFINE_YAML_FILE = 1;
    const DEFINE_JSON_FILE = 2;

    /** �t�@�C�����Ȃ��Ƃ��ǂ߂Ȃ��Ƃ� */
    const ERR_FILE_NOT_READ = 1;
    /** ��`�����������n */
    const ERR_INVALID_DEFINE = 2;
    /** �l�����������n */
    const ERR_INVALID_VALUE = 3;

    /** @var  array validation rule */
    private $define;

    private $path = ['$arg'];

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
        if (!isset($def['type'])) {
            throw new hashValidatorException('invalid define', self::ERR_INVALID_DEFINE);
        }
        switch ($def['type']) {
            case 'int':
                return $this->_validateInt($arg, $def);
            case 'float':
                return $this->_validateFloat($arg, $def);
            case 'string':
                return $this->_validateString($arg, $def);
            case 'enum':
                return $this->_validateEnum($arg, $def);
            case 'hash':
                return $this->_validateHash($arg, $def);
            case 'callback':
                return $this->_validateCallBack($arg, $def);
            default:
                throw new hashValidatorException('invalid type', self::ERR_INVALID_DEFINE);
        }
    }

    private function _validateInt($arg, $def)
    {
        if (!is_numeric($arg)) {
            throw new hashValidatorException('invalid int value:' . var_export($arg, true) . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        $val = (int)$arg;
        if (isset($def['min']) && $val < $def['min']) {
            throw new hashValidatorException('input:' . $val . ' less than ' . $def['min'] . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $val > $def['max']) {
            throw new hashValidatorException('input:' . $val . ' grater than ' . $def['min'] . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        return $val;
    }

    private function _validateFloat($arg, $def)
    {
        if (!is_numeric($arg)) {
            throw new hashValidatorException('invalid int value:' . var_export($arg, true) . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        $val = (float)$arg;
        if (isset($def['min']) && $val < $def['min']) {
            throw new hashValidatorException('input:' . $val . ' less than ' . $def['min'] . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $val > $def['max']) {
            throw new hashValidatorException('input:' . $val . ' grater than ' . $def['min'] . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        return $val;
    }

    private function _validateString($arg, $def)
    {
        if (!is_scalar($arg)) {
            throw new hashValidatorException('invalid int value:' . var_export($arg, true), self::ERR_INVALID_VALUE);
        }
        $val = (string)$arg;
        $len = strlen($val);
        if (isset($def['min']) && $len < $def['min']) {
            throw new hashValidatorException('input length:' . $len . ' less than ' . $def['min'] . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $len > $def['max']) {
            throw new hashValidatorException('input length:' . $len . ' grater than ' . $def['min'] . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['preg']) && !preg_match($def['preg'], $arg)) {
            throw new hashValidatorException('input:' . $arg . ' not match ' . $def['preg'] . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        return $val;
    }

    private function _validateEnum($arg, $def)
    {
        if (!in_array($arg, $def['value'])) {
            throw new hashValidatorException('input:' . $arg . ' not found in [' . implode(',', $def['value']) . ']' . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        return $arg;
    }

    private function _validateCallBack($arg, $def)
    {
        try {
            if (call_user_func($def['value'], $arg)) {
                return $arg;
            }

        } catch (\Exception $e) {

        }
        $func = is_array($arg) ? implode('::', $arg) : $arg;
        throw new hashValidatorException('input:' . $arg . ' invalid ' . $func . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
    }

    private function _validateHash($arg, $def)
    {
        $return = [];
        foreach ($def['value'] as $key => $elmDef) {
            if (false === array_key_exists($key, $arg)) {
                if (false !== array_key_exists('optional', $elmDef)) {
                    continue;
                } else {
                    throw new hashValidatorException('undefined key:' . $key . ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
                }
            }
            array_push($this->path, '[' . $key . ']');
            $return[$key] = $this->_validate($arg[$key], $def['value'][$key]);
            array_pop($this->path);
        }
        return $return;
    }


}

class hashValidatorException extends \Exception
{
}
