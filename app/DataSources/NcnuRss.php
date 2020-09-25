<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetriveException;
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

        $this->url = 'https://www.ncnu.edu.tw/ncnuweb/ann/RSS.aspx';
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetriveException
     */
    public function getContent() : array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url);

            $fileContents = $response->getBody()->getContents();

            $simpleXml = simplexml_load_string($fileContents, 'SimpleXMLElement', LIBXML_NOCDATA);

            return @json_decode(@json_encode($simpleXml), 1);
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }
}