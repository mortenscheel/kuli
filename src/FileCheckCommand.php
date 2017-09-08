<?php
/**
 * Created by PhpStorm.
 * User: morten
 * Date: 07/09/2017
 * Time: 18.52
 */

namespace Kuli;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FileCheckCommand extends KuliCommand
{
    protected function configure()
    {
        $this->addArgument('path', InputArgument::REQUIRED, 'File path')
             ->setName('file:check')
             ->setDescription('Checks if a file exists')
             ->setHelp('Check if files are okay.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $absolutePath = $this->fileSystem->translatePathArgument($path);
        $exists = file_exists($absolutePath);
        $readable = is_readable($absolutePath);
        $writable = is_writable($absolutePath);
        $executable = is_executable($absolutePath);
        if (!$exists) {
            $output->writeln('<error>File or directory does not exist.</error>');
        } else if (!$readable) {
            $output->writeln('<warning>File or directory is not readable.</warning>');
        } else {
            if ($writable && $executable) {
                $output->writeln('<comment>All permissions okay.</comment>');
            } else if (!$writable && !$executable) {
                $output->writeln('<comment>Not writable or executable.</comment>');
            } else if ($writable) {
                $output->writeln('<comment>Not executable.</comment>');
            } else {
                $output->writeln('<comment>Not writable.</comment>');
            }
        }
    }

}