<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetriveException;
use Exception;

class Ncdr extends Source
{
    private $baseUrl;

    /**
     * Ncdr constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->baseUrl = 'https://alerts.ncdr.nat.gov.tw';
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetriveException
     */
    public function getContent() : array
    {
        try {
            $response = $this->httpClient->request('GET', $this->baseUrl . '/JSONAtomFeeds.ashx');

            $fileContents = json_decode($response->getBody()->getContents(), true);

            if (!is_array($fileContents['entry'])) {
                $fileContents['entry'] = [$fileContents['entry']];
            }

            return $fileContents;
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStopWorkingInfo()
    {
        $apiKey = config('ncdr.key');

        if (empty($apiKey)) {
            return ['status' => -1, 'data' => ''];
        }

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->baseUrl . '/api/datastore?format=json&capcode=WSC&apikey=' . $apiKey
            );

            $fileContents = json_decode($response->getBody()->getContents(), true);

            return $fileContents;
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }
}