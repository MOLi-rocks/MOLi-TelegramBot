<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetrieveException;
use Exception;

class MoliDoorStatus extends Source
{
    private $url;

    /**
     * MoliKktix constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = 'https://bot.moli.rocks:8000';
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetrieveException
     */
    public function getContent() : array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url);

            $fileContents = json_decode($response->getBody()->getContents(), true);

            return $fileContents;
        } catch (Exception $e) {
            throw new DataSourceRetrieveException($e->getMessage(), $e->getCode());
        }
    }
}
