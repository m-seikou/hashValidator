<?php
/**
 * Created by PhpStorm.
 * User: 423
 * Date: 2015/07/27
 * Time: 11:21
 */

namespace mihoshi\hashValidator\exceptions;

class loaderException extends \Exception
{

	/** ファイルがないとか読めないとか */
	const ERR_FILE_NOT_READ = 1;

}
