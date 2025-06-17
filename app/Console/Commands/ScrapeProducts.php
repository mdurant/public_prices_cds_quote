<?php

namespace App\Console\Commands;

use App\Services\ScraperService;
use Illuminate\Console\Command;

class ScrapeProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape product prices from the website and store in database by IntegralTech Consulting Spa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando proceso de scraping By: IntegralTech Consulting Spa ...');
        $scraper = new ScraperService();
        $scraper->scrapeProducts();
        $this->info('Scraping finalizado.');
    }
}