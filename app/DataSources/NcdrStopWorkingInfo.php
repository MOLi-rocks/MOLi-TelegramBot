<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetriveException;
use Exception;

class NcdrStopWorkingInfo extends Source
{
    private $baseUrl;

    /**
     * NcdrStopWorkingInfo constructor.
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
        $apiKey = config('ncdr.key');

        if (empty($apiKey)) {
            return ['status' => -1, 'data' => ''];
        }

        try {
            $response = $this->httpClient->request(
                'GET',
                $this->baseUrl . '/api/datastore?format=json&capcode=WSC&apikey=' . $apiKey
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }
}