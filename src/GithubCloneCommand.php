<?php

namespace Kuli;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GithubCloneCommand extends KuliCommand
{
    protected function configure()
    {
        $this->addArgument('repo', InputArgument::REQUIRED, 'Github repository')
             ->addArgument('target', InputArgument::OPTIONAL, 'Target folder')
             ->setName('github:clone')
             ->setDescription('Clones a Github repository')
             ->setHelp('Clone github repository');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = $input->getArgument('repo');
        $target = $input->getArgument('target') ?: '.';
        $absolutePath = $this->fileSystem->translatePathArgument($target);
        $command = "git clone git@github.com:{$repo} {$absolutePath}";
        exec($command);
        $output->writeln("<comment>Cloned {$repo} to {$target}</comment>");
    }
}
