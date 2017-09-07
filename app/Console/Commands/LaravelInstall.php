<?php

namespace App\Console\Commands;

use App\FileSystem;
use Illuminate\Console\Command;

class LaravelInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel:install 
                            {repo : Github repository e.g. laravel/framework}
                            {target=. : Target directory}
                            {--skip-migrate : Don\'t run artisan migrate}
                            {--seed : Seed database}
                            {--no-bin-links : Install node dependencies without bin links}
                            {--npm : Use npm in stead of yarn}
                            {--envfile=.env.example : Template .env file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clone and install a Laravel project';
    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * Create a new command instance.
     *
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        parent::__construct();
        $this->fileSystem = $fileSystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commands = $this->getShellCommands();
        foreach ($commands as $command) {
            $this->info("Running " . $command);
            ob_start();
            shell_exec($command);
            $buffered = ob_get_clean();
            $this->comment($buffered);
        }
    }

    /**
     * @param $repo
     * @param $target
     * @param $targetPath
     * @param $envfile
     * @return array
     */
    protected function getShellCommands()
    {
        $repo = $this->argument('repo');
        $target = $this->argument('target');
        $targetPath = $this->fileSystem->translatePathArgument($target);
        $envfile = $this->option('envfile');
        $commands = [
            "php artisan github:clone {$repo} {$target}",
            "cd {$targetPath}",
            "cp $envfile .env",
            "php artisan key:generate",
            "composer install",
        ];
        if (!$this->option('skip-migrate')) {
            $commands[] = "php artisan migrate";
        }
        if ($this->option('seed')) {
            $commands[] = "php artisan db:seed";
        }
        $commands[] = ($this->option('npm') ? "npm install" : "yarn") . ($this->option('no-bin-links') ? " --no-bin-links" : "");
        $commands[] = $this->option('npm') ? "npm run dev" : "yarn run dev";
        return $commands;
    }
}
