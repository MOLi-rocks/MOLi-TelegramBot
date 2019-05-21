<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use MOLiBot\Repositories\PublishedKKTIXRepository;

class MOLiDayService
{
    private $publishedKKTIXRepository;

    public function __construct(PublishedKKTIXRepository $publishedKKTIXRepository)
    {
        $this->publishedKKTIXRepository = $publishedKKTIXRepository;
    }

    public function getEvents()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request('GET', 'https://moli.kktix.cc/events.json', [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'Accept' => 'application/json',
                    'cache-control' => 'no-cache'
                ],
                'timeout' => 10
            ]);

            return $response->getBody()->getContents();
        } catch (GuzzleHttpTransferException $e) {
            return '';
        }
    }

    public function checkEventPublished($url)
    {
        return $this->publishedKKTIXRepository->checkEventPublished($url);
    }

    public function storePublishedEvent($event)
    {
        return $this->publishedKKTIXRepository->storePublishedEvent($event);
    }
}