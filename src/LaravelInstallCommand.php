<?php

namespace Kuli;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LaravelInstallCommand extends KuliCommand
{
    protected function configure()
    {
        $this->setName('laravel:install')
             ->setDescription('Clone and install a Laravel project from Github')
             ->addArgument('repo', InputArgument::REQUIRED, 'Github repository name')
             ->addArgument('target', InputArgument::OPTIONAL, 'Target folder', '.')
             ->addOption('migrate', 'm', InputOption::VALUE_OPTIONAL, 'Skip migration')
             ->addOption('seed', 's', InputOption::VALUE_OPTIONAL, 'Run DB seeder')
             ->addOption('npm', null, InputOption::VALUE_OPTIONAL, 'Use NPM in stead of yarn')
             ->addOption('no-bin-links', null, InputOption::VALUE_OPTIONAL, 'Install node packages without bin links')
             ->addOption('env-file', null, InputOption::VALUE_OPTIONAL, 'Use a custom .env file', '.env.example');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = $input->getArgument('repo');
        $target = $input->getArgument('target');
        $targetPath = $this->fileSystem->translatePathArgument($target);
        $envfile = $input->getOption('env-file');
        $migrate = (bool) $input->getOption('migrate');
        $seed = (bool) $input->getOption('seed');
        $useNpm = (bool) $input->getOption('npm');
        $noBinLinks = (bool) $input->getOption('no-bin-links');
        $githubCloneCommand = $this->getApplication()->find('github:clone');
        $arguments = array(
            'command' => 'github:clone',
            'repo'    => $repo,
            'target'  => $target
        );
        $cloneInput = new ArrayInput($arguments);
        $returnCode = $githubCloneCommand->run($cloneInput, $output);
        $commands = [
            "cp $envfile .env",
            "composer install",
            "php artisan key:generate",
        ];
        if ($migrate) {
            $commands[] = "php artisan migrate";
        }
        if ($seed) {
            $commands[] = "php artisan db:seed";
        }
        $commands[] = ($useNpm ? "npm install" : "yarn") . ($noBinLinks ? " --no-bin-links" : "");
        $commands[] = $useNpm ? "npm run dev" : "yarn run dev";
        foreach ($commands as $command) {
            $output->writeln('<comment>Running ' . $command . '</comment>');
            exec("cd {$targetPath} && " . $command);
        }
    }
}
