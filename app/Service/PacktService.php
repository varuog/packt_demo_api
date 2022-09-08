<?php

namespace App\Service;

use Illuminate\Support\Facades\Http;

class PacktService
{
    protected $httpService;

    public function __construct()
    {
        // dd(config('services.packt.url'));
        $this->httpService = Http::withOptions([
            //'debug' => true,
            'base_url' => config('services.packt.url'),
        ])
        ->withBasicAuth(config('services.packt.key'), config('services.packt.secret'));
    }

    /**
     * @param  int  $limit default 100
     * @param  int  $page default 1
     */
    public function fetchAll($page = 1, $limit = 100)
    {
        $productListApiUrl = sprintf('%s/api/v1/products', config('services.packt.url'));
        // dd($productListApiUrl);
        $productsReq = $this->httpService->get($productListApiUrl);
        $products = $productsReq->json();

        return $products;
    }
}
