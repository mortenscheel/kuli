<?php
/**
 * Created by PhpStorm.
 * User: morten
 * Date: 07/09/2017
 * Time: 15.24
 */

namespace Kuli;

/**
 * Class FileSystem
 * @package Kuli
 */
class FileSystem
{
    /**
     * Resolves path arguments relative to the current working directory
     * @param string $path
     * @return string
     */
    public function translatePathArgument($path)
    {
        // Don't translate absolute paths
        if (preg_match('/^\//', $path)) {
            return $path;
        }
        return getcwd() . "/" . $path;
    }
}