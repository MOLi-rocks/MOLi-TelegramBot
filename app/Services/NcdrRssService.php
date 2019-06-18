<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use MOLiBot\Repositories\PublishedNcdrRssRepository;

class NcdrRssService
{
    private $publishedNcdrRssRepository;

    public function __construct(PublishedNcdrRssRepository $publishedNcdrRssRepository)
    {
        $this->publishedNcdrRssRepository = $publishedNcdrRssRepository;
    }

    public function getNcdrRss()
    {
        $client = new GuzzleHttpClient();

        try {
            $response = $client->request(
                'GET',
                'https://alerts.ncdr.nat.gov.tw/JSONAtomFeeds.ashx',
                [
                    'headers' => [
                        'User-Agent' => 'MOLi Bot',
                        'Accept-Encoding' => 'gzip',
                        'cache-control' => 'no-cache'
                    ],
                    'timeout' => 10
                ]
            );
        } catch (GuzzleHttpTransferException $e) {
            return $e->getCode();
        }

        $fileContents = json_decode($response->getBody()->getContents());

        if (!is_array($fileContents->entry)) {
            $fileContents->entry = [$fileContents->entry];
        }

        return $fileContents;
    }

    public function checkRssPublished($id)
    {
        return $this->publishedNcdrRssRepository->checkRssPublished($id);
    }

    public function storePublishedRss($id, $category)
    {
        return $this->publishedNcdrRssRepository->storePublishedRss($id, $category);
    }

    /**
     * @param array $excludeId
     * @return boolean
     */
    public function deletePublishedRecordWithExcludeId($excludeId)
    {
        return $this->publishedNcdrRssRepository->deletePublishedRecordWithExcludeId($excludeId);
    }
}
