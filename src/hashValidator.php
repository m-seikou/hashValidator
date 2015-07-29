<?php

namespace mihoshi\hashValidator;

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
        $this->define = $this->loader->load($arg);
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
        return $this->define;
    }


    public function validate($arg)
    {
        return $this->_validate($arg, $this->define);
    }

    /**
     * @param $arg
     * @param $def
     * @return array|float|int|string
     * @throws hashValidatorException
     */
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
            case 'list':
                return $this->_validateList($arg, $def);
            default:
                throw new hashValidatorException('invalid type', self::ERR_INVALID_DEFINE);
        }
    }

    private function _validateInt($arg, $def)
    {
        if (!is_numeric($arg)) {
            throw new hashValidatorException('invalid int value:' . var_export($arg, true) .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        $val = (int)$arg;
        if (isset($def['min']) && $val < $def['min']) {
            throw new hashValidatorException('input:' . $val . ' less than ' . $def['min'] .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $val > $def['max']) {
            throw new hashValidatorException('input:' . $val . ' grater than ' . $def['min'] .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        return $val;
    }

    private function _validateFloat($arg, $def)
    {
        if (!is_numeric($arg)) {
            throw new hashValidatorException('invalid int value:' . var_export($arg, true) .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        $val = (float)$arg;
        if (isset($def['min']) && $val < $def['min']) {
            throw new hashValidatorException('input:' . $val . ' less than ' . $def['min'] .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $val > $def['max']) {
            throw new hashValidatorException('input:' . $val . ' grater than ' . $def['min'] .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
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
            throw new hashValidatorException('input length:' . $len . ' less than ' . $def['min'] .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $len > $def['max']) {
            throw new hashValidatorException('input length:' . $len . ' grater than ' . $def['min'] .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['preg']) && !preg_match($def['preg'], $arg)) {
            throw new hashValidatorException('input:' . $arg . ' not match ' . $def['preg'] .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        return $val;
    }

    private function _validateEnum($arg, $def)
    {
        if (!in_array($arg, $def['value'])) {
            throw new hashValidatorException('input:' . $arg . ' not found in [' . implode(',', $def['value']) . ']' .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
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
        throw new hashValidatorException('input:' . $arg . ' invalid ' . $func .
            ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
    }

    private function _validateHash($arg, $def)
    {
        $return = [];
        foreach ($def['value'] as $key => $elmDef) {
            if (false === array_key_exists($key, $arg)) {
                if (false !== array_key_exists('optional', $elmDef)) {
                    continue;
                } else {
                    throw new hashValidatorException('undefined key:' . $key .
                        ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
                }
            }
            array_push($this->path, '[' . $key . ']');
            $return[$key] = $this->_validate($arg[$key], $def['value'][$key]);
            array_pop($this->path);
        }
        return $return;
    }


    private function _validateList($arg, $def)
    {
        $cnt = count($arg);
        if (isset($def['min']) && $def['min'] > $cnt) {
            throw new hashValidatorException('fewer element num:' . $cnt .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        if (isset($def['max']) && $def['max'] < $cnt) {
            throw new hashValidatorException('too meany element num:' . $cnt .
                ' at ' . implode('', $this->path), self::ERR_INVALID_VALUE);
        }
        $return = [];
        foreach ($arg as $key => $val) {
            array_push($this->path, '[' . $key . ']');
            $return[$key] = $this->_validate($arg[$key], $def['value']);
            array_pop($this->path);
        }
        return $return;
    }
}

class hashValidatorException extends \Exception
{
}
