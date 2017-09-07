<?php

namespace App\Console\Commands;

use App\FileSystem;
use Illuminate\Console\Command;

class GithubClone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:clone {repo} {target?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clone Github repository';
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
        $repo = $this->argument('repo');
        $target = $this->argument('target') ?: '.';
        $targetPath = $this->fileSystem->translatePathArgument($target);
        $command = "git clone git@github.com:{$repo} {$targetPath}";
        exec($command);
        $this->info("Cloned {$repo} to {$target}");
    }
}
