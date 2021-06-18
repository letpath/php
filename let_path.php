<?php

// 'https://php.letpath.com/let_path.php',
//namespace LetPath;


function getDirContents($dir, &$results = array())
{
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
    $separator = '*';

    if (empty($path)) {
        throw new Exception("path: $path is empty");
    }

    $path_list = [];
    if (!is_array($path)) {
        $path_list[] = $path;
    } else {
        $path_list = $path;
    }

    $txt = [];
    foreach ($path_list as $path_item) {
        $parts = (explode($separator, $path_item));

        if ($parts >= 1) {

            // TODO: get first occurence
            // if more make it later in the next time
            // TODO: make recursive, if more than one

            $path_prefix = $parts[0];
            $path_suffix = $parts[1];
//        $files = scandir($path);
            $files = array_diff(scandir($path_prefix), array('.', '..'));

            foreach ($files as $path_subitem) {


//                $path_subitem = str_replace("*", $path_subitem, $path_item);
                $path_subitem = $path_prefix . $path_subitem . $path_suffix;
//            var_dump($path_subitem);

                // check if path exist
                if (!path_exists($path_subitem)) {
//			throw new Exception("path: " . $path_item . " not exist ");
                    continue;
                }

                if (is_callable($callback)) {
                    $callback($path_subitem);
//                    continue;
                }

//            echo "<a href='$file'>$file</a>";
                $txt[] = $path_subitem;

            }
        }

        // check if path exist
        if (!path_exists($path_item)) {
//			throw new Exception("path: " . $path_item . " not exist ");
            continue;
        }

        // Check Content
//		$file = file_get_contents($path, true);
//		if (empty($file)) {
//			throw new Exception("Content from path: $path is empty");
//            continue;
//        }

        if (is_callable($callback)) {
            $callback($path_item);
//            continue;
        }

        $txt[] = $path_item;
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
