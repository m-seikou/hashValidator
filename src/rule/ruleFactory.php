<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/08/03
 * Time: 10:18
 */

namespace mihoshi\hashValidator\rule;

use mihoshi\hashValidator\exceptions\invalidRuleException;

class ruleFactory
{
    /**
     * @var array [ルール => クラス名] ロード済みのルール
     */
    private static array $typeList = [];

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
        // ロード済みルール
        if (array_key_exists($rule, self::$typeList)) {
            return self::$typeList[$rule];
        }
        // hashValidatorで定義済みのルール
        if (class_exists(__NAMESPACE__ . '\\' . $rule . 'Rule')) {
            return self::$typeList[$rule] = __NAMESPACE__ . '\\' . $rule . 'Rule';
        }
        // 独自のruleInterfaceを実装したクラス
        if (class_exists($rule)) {
            return self::$typeList[$rule] = $rule;
        }
        throw new invalidRuleException('rule not found:' . $rule);
    }
}
