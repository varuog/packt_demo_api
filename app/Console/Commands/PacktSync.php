<?php

namespace App\Console\Commands;

use App\Jobs\BookSyncJob;
use App\Service\PacktService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
    protected $description = 'Packt API Sync';

    protected PacktService $packtService;

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
        $booksData = $this->packtService->fetchAll();
        // dd($booksData['products']);
        if(isset($booksData['products']) && is_array($booksData['products'])) {
            foreach($booksData['products'] as $bookData) {
                dispatch(new BookSyncJob($bookData));
            }
        } else {
            Log::error('Packt API error');
        }
        

        return 0;
    }
}
