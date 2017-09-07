<?php
/**
 * Created by PhpStorm.
 * User: morten
 * Date: 07/09/2017
 * Time: 15.24
 */

namespace App;


class FileSystem
{
    public function translatePathArgument($path)
    {
        // Don't translate absolute paths
        if (preg_match('/^\//', $path)) {
            return $path;
        }
        return getcwd() . "/" . $path;
    }
}