<?php

namespace App\Console\Commands;

use App\FileSystem;
use Illuminate\Console\Command;

class FileCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:check {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
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
        $path = $this->fileSystem->translatePathArgument($this->argument('path'));
        $exists = file_exists($path);
        $readable = is_readable($path);
        if ($readable) {
            $this->info("All good");
        } else if ($exists) {
            $this->warn("Not readable");
        } else {
            $this->error("Doesn't exist");
        }
    }
}
