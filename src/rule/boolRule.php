<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/29
 * Time: 17:43
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidDataException;

final class boolRule extends abstractRule
{
    private $null = false;
    private $strict = true;

    public function __construct($rule)
    {
        parent::__construct($rule);
        if (isset($rule['arrow_null'])) {
            $this->null = $rule['arrow_null'];
        }
        if(isset($rule['strict'])){
            $this->strict = $rule['strict'];
        }
    }

    public function check($value)
    {
        if ($value === null && $this->null) {
            return null;
        }
        if($this->strict){
            if($value === true || $value === false){
                return $value;
            }
            throw new invalidDataException('invalid int value:' . var_export($value, true), 0, null, $this->message);
        }
        /**
         * @see https://www.php.net/manual/ja/language.types.boolean.php
         */
        $value = (bool)$value;
        return $value;
    }
}
