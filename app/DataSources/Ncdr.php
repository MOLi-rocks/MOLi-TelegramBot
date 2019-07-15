<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetriveException;
use Exception;

class Ncdr extends Source
{
    private $url;

    /**
     * Ncdr constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = 'https://alerts.ncdr.nat.gov.tw/JSONAtomFeeds.ashx';
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetriveException
     */
    public function getContent() : array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url);

            $fileContents = json_decode($response->getBody()->getContents(), true);

            if (!is_array($fileContents['entry'])) {
                $fileContents['entry'] = [$fileContents['entry']];
            }

            return $fileContents;
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }
}