<?php

//namespace LetPath;

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
		throw new Exception("Url: $path is empty");
	}

	$urls = [];
	if (!is_array($url)) {
		$urls[] = $url;
	} else {
		$urls = $url;
	}

	$txt = '';
	foreach ($urls as $url_item) {
		// check if URL exist
		if (!url_exists($url_item)) {
			throw new Exception("Url: " . $url_item . " not exist ");
		}

		// Check Content
		$file = file_get_contents($url, true);
		if (empty($file)) {
			throw new Exception("Content from Url: $url is empty");
		}

		$txt .= $file;
	}

	if (is_callable($callback)) {
		return $callback($txt);
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
	public $url = '';

	/**
	 * LetPath constructor.
	 * @param $url
	 */
	function __construct($url)
	{
		$this->url = $url;
		$this->json = let_path($url);
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
 * @param string $url
 * @return bool
 */
function url_exists($url)
{
	if (curl_init($url) === false) {
		return false;
	}

	$headers = @get_headers($url);
	if (strpos($headers[0], '200') === false) {
		return false;
	}

	return true;
}
