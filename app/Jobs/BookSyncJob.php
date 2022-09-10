<?php

namespace App\Jobs;

use App\Service\BookService;
use App\Service\PacktService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookSyncJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $bookData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $bookData)
    {
        $this->bookData = $bookData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(BookService $bookService)
    {

        $bookService = $bookService;
        Log::info('Product bulk upload started');
        // Log::info($this->bookData);
        $bookService->bulkAdd($this->bookData);
        Log::info('Product bulk upload ended');
    }
}
