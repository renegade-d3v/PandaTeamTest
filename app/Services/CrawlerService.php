<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $url
     * @return string
     * @throws GuzzleException
     */
    public function fetchHtmlPage(string $url): string
    {
        $response = $this->client->get($url);

        return (string) $response->getBody();
    }

    /**
     * @param string $html
     * @return int|null
     */
    public function findAnnouncementId(string $html): ?int
    {
        $crawler = new Crawler($html);
        $elements = $crawler->filter('span');

        foreach ($elements as $element) {
            if (preg_match('/ID:\s*(\d+)/', $element->textContent, $matches)) {
                return (int) $matches[1];
            }
        }

        return null;
    }
}
