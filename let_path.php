<?php

// 'https://php.letpath.com/let_path.php',
//namespace LetPath;

/**
 * @param $dir
 * @param null $callback
 * @param array $results
 *
 * @return array|mixed
 *
 * @throws Exception
 */
function let_path($dir, $callback = null, &$results = array())
{
	$sep = '*';
	$parts = explode($sep, $dir);
	$size = count($parts);

	// IF one or more * in the path
	if ($size === 2) {
		$path_prefix = $parts[0];
		$path_suffix = $parts[1];
		$files = scandir($path_prefix);
	} else if ($size > 2) {
		$path_prefix = (string)$parts[0];
		unset($parts[0]);
		$path_suffix = (string)implode($sep, $parts);
		$files = scandir($path_prefix);
	} else {
		$files = scandir($dir);
	}


	foreach ($files as $key => $value) {

		$path = $dir . DIRECTORY_SEPARATOR . $value;

		if ($size > 2) {

			$path = $path_prefix . $value . $path_suffix;
			let_path($path, $callback, $results);

		} else {

			if ($size === 2) {
				$path = $path_prefix . $value . $path_suffix;
			}

			if ($value != "." && $value != "..") {

				if (!is_dir($path)) {
					// ONLY EXISTING FILES
					if (file_exists($path)) {
						$results[] = $path;

						if (is_callable($callback)) {
							$callback($path);
						}
					}
				} else {
					let_path($path, $callback, $results);
				}
			}

		}

	}

	return $results;
}


/**
 * Class LetPath
 */
class LetPath
{
	/** @var array|mixed */
	public $json = [];

	/** @var string */
	public $path = '';

	/**
	 * LetPath constructor.
	 * @param $path
	 */
	function __construct($path)
	{
		$this->path = $path;
		$this->json = let_path($path);
	}

	/**
	 * @return mixed
	 */
	function first()
	{
		return $this->json[0];
	}

	/**
	 * @param $callback
	 */
	function each($callback)
	{
		foreach ($this->json as $item) {
			$callback($item);
		}
	}
}


/**
 * @param string $path
 * @return bool
 */
function path_exists($path)
{
	if (file_exists($path) === false) {
		return false;
	}

	return true;
}
