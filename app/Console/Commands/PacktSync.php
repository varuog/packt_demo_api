<?php

namespace App\Console\Commands;

use App\Service\PacktService;
use Illuminate\Console\Command;

class PacktSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'packt:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $packtService;

    public function __construct(PacktService $packtService)
    {
        parent::__construct();
        $this->packtService = $packtService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $books = $this->packtService->fetchAll();
        dd($books);

        return 0;
    }
}
