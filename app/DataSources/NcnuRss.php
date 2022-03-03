<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetrieveException;
use Exception;

class NcnuRss extends Source
{
    private $url;

    /**
     * NcnuRss constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = 'https://api.ncnu.edu.tw/API/get.aspx?json=info_ncnu&month_include=1&include_expired=f';
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetrieveException
     */
    public function getContent() : array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url);

            $fileContents = $response->getBody()->getContents();

            return json_decode($fileContents, 1);
        } catch (Exception $e) {
            throw new DataSourceRetrieveException($e->getMessage() ?: 'Can\'t Retrieve Data', $e->getCode() ?: 502);
        }
    }
}