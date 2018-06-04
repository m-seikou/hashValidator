<?php

namespace mihoshi\hashValidator\loaders;

use mihoshi\hashValidator\interfaces\loaderInterface;

final class hashLoader implements loaderInterface
{
	public function load($array)
	{
		return $array;
	}

}
