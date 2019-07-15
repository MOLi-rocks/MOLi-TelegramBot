<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetriveException;
use Exception;
use SoapBox\Formatter\Formatter;

class Ncnu extends Source
{
    private $url;

    /**
     * Ncnu constructor.
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

            $formatted = Formatter::make($fileContents, Formatter::XML)->toArray();

            if (!is_array($formatted['channel']['item'])) {
                $formatted['channel']['item'] = [$formatted['channel']['item']];
            }

            return $formatted;
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }
}