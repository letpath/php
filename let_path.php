<?php

//namespace LetPath;


function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            $results[] = $path;
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }

    return $results;
}
/**
 * @param $path
 * @param null $callback
 * @return string
 *
 * @throws Exception
 */
function let_path($path, $callback = null)
{
	if (empty($path)) {
		throw new Exception("path: $path is empty");
	}

	$paths = [];
	if (!is_array($path)) {
		$paths[] = $path;
	} else {
		$paths = $path;
	}

	$txt = '';
	foreach ($paths as $path_item) {
		// check if path exist
		if (!path_exists($path_item)) {
			throw new Exception("path: " . $path_item . " not exist ");
		}

		// Check Content
		$file = file_get_contents($path, true);
		if (empty($file)) {
			throw new Exception("Content from path: $path is empty");
		}

        if (is_callable($callback)) {
            return $callback($txt);
        }

		$txt .= $file;
	}


	return $txt;
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
