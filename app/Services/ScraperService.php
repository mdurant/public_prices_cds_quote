<?php

namespace App\Services;

use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;


class ScraperService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = env('BASE_URL_SCRAPING_SERVICE');
    }

    public function scrapeProducts()
    {
        $page = 1;
        $hasData = true;

        while ($hasData) {
            $url = $this->baseUrl . $page;
            Log::info("Scraping página: {$url}");

            try {
                $response = $this->client->get($url);
                $crawler = new Crawler($response->getBody()->getContents());
                $products = $this->extractProducts($crawler);

                if (empty($products)) {
                    $hasData = false;
                    Log::info("No se encontraron más productos en la página {$page}. Finalizando.");
                    break;
                }

                $this->saveProducts($products);
                $page++;
                sleep(1); // Retardo para evitar bloqueos
            } catch (\Exception $e) {
                Log::error("Error en la página {$page}: {$e->getMessage()}");
                $hasData = false;
            }
        }
    }

    protected function extractProducts(Crawler $crawler): array
    {
        $products = [];

        $crawler->filter('table tr')->each(function (Crawler $row, $i) use (&$products) {
            if ($i === 0) return; // Saltar encabezado

            $columns = $row->filter('td');
            if ($columns->count() >= 4) {
                $products[] = [
                    'description' => trim($columns->eq(0)->text()),
                    'fonasa_code' => trim($columns->eq(1)->text()),
                    'fonasa_patient_price' => $this->parsePrice($columns->eq(2)->text()),
                    'private_price' => $this->parsePrice($columns->eq(3)->text()),
                ];
            }
        });

        return $products;
    }

    protected function parsePrice(string $price): float
    {
        // Convertir "3.570" a 3570.00
        return (float) str_replace(['.', ','], ['', '.'], trim($price));
    }

    protected function saveProducts(array $products)
    {
        foreach ($products as $product) {
            try {
                Product::updateOrCreate(
                    ['fonasa_code' => $product['fonasa_code']],
                    [
                        'description' => $product['description'],
                        'fonasa_patient_price' => $product['fonasa_patient_price'],
                        'private_price' => $product['private_price'],
                    ]
                );
            } catch (\Exception $e) {
                Log::error("Error al guardar producto {$product['fonasa_code']}: {$e->getMessage()}");
            }
        }
    }
}