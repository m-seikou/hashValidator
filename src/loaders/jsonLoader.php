<?php

namespace mihoshi\hashValidator\loaders;

use mihoshi\hashValidator\interfaces\loaderInterface;
use mihoshi\hashValidator\exceptions\loaderException;

class jsonLoader implements loaderInterface
{
	/**
	 * @param string $fileName
	 * @return mixed
	 * @throws loaderException
	 */
	public function load($fileName)
	{
		try {
			if (!file_exists($fileName)) {
				throw new loaderException('file not found:' . $fileName, loaderException::ERR_FILE_NOT_READ);
			}
			if (empty($file = file_get_contents($fileName))) {
				throw new loaderException('file not read:' . $fileName, loaderException::ERR_FILE_NOT_READ);
			}
			if (null === $return = json_decode($file, true)) {
				throw new loaderException('file not json:' . $fileName, loaderException::ERR_FILE_NOT_READ);
			}
		} catch (loaderException $e) {
			throw $e;
		} catch (\Exception $e) {
			throw new loaderException($e->getMessage(), loaderException::ERR_FILE_NOT_READ, $e);
		}
		$return = $this->resolveIncludeFile($return, dirname($fileName));
		return $return;
	}

	private function resolveIncludeFile($def, $path)
	{
		if (!is_array($def)) {
			return $def;
		}
		foreach ($def as &$d) {
			if (isset($d['include'])) {
				$fileName = realpath($path . DIRECTORY_SEPARATOR . $d['include']);
				unset($d['include']);
				$d = array_merge_recursive($d, $this->load($fileName));
			}
			$d = $this->resolveIncludeFile($d, $path);
		}
		return $def;
	}
}
