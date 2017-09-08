<?php
/**
 * Created by PhpStorm.
 * User: morten
 * Date: 07/09/2017
 * Time: 18.58
 */

namespace Kuli;


use Symfony\Component\Console\Command\Command;

/**
 * Class KuliCommand
 * @package Kuli
 */
abstract class KuliCommand extends Command
{
    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * KuliCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileSystem = new FileSystem;
    }
}