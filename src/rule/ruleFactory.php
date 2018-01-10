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
	private static $typeList = [];

	/**
	 * @var array [namespace => directory] ルールクラスの格納ディレクトリ
	 */
	private static $ruleDir = [__NAMESPACE__ => __DIR__];

	/**
	 * ルールクラスの格納先を追加
	 * @param $dirName
	 * @param $nameSpace
	 */
	public static function addRuleDir($dirName, $nameSpace)
	{
		unset(self::$ruleDir[__NAMESPACE__]);
		$new = [];
		foreach (self::$ruleDir as $nameSpace => $dirName) {
			$new[$nameSpace] = $dirName;
		}
		$new[$nameSpace] = $dirName;
		$new[__NAMESPACE__] = __DIR__;

		self::$ruleDir = $new;
	}

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
	private static function getClassName($rule)
	{
		if (array_key_exists($rule, self::$typeList)) {
			return self::$typeList[$rule];
		}
		foreach (self::$ruleDir as $nameSpace => $dir) {
			if (file_exists($dir . DIRECTORY_SEPARATOR . $rule . 'Rule.php')) {
				require_once $dir . DIRECTORY_SEPARATOR . $rule . 'Rule.php';
				self::$typeList[$rule] = $nameSpace . '\\' . $rule . 'Rule';
				return self::$typeList[$rule];
			}
		}
		throw new invalidRuleException('rule not found:' . $rule);
	}
}
