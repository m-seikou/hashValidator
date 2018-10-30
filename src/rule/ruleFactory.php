<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/08/03
 * Time: 10:18
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidRuleException;

final class ruleFactory
{
    /**
     * @var array [ルール => クラス名] ロード済みのルール
     */
    private static $typeList = [];

    /**
     * ルールクラスの作成
     * @param array $rule
     * @return mixed
     */
    public static function getInstance(array $rule)
    {
        try {
            $className = self::getClassName($rule['type']);
            unset($rule['type']);
            return new $className($rule);
        } catch (invalidRuleException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new invalidRuleException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * ルールクラス名の取得
     * @param $rule
     * @return string
     */
    private static function getClassName($rule): string
    {
        try {
            if (array_key_exists($rule, self::$typeList)) {
                return self::$typeList[$rule];
            }
            if (class_exists(__NAMESPACE__ . '\\' . $rule . 'Rule')) {
                self::$typeList[$rule] = __NAMESPACE__ . '\\' . $rule . 'Rule';
            } elseif (class_exists($rule)) {
                self::$typeList[$rule] = $rule;
            } else {
                throw new invalidRuleException('rule not found:' . $rule);
            }
        } catch (invalidRuleException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new invalidRuleException($e->getMessage(), $e->getCode(), $e);
        }
        return self::$typeList[$rule];
    }
}
