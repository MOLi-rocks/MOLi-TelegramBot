<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetrieveException;
use Exception;

class NcnuStaffContact extends Source
{
    private $baseUrl;

    /**
     * NcnuStaffContact constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->baseUrl = 'http://ccweb1.ncnu.edu.tw/telquery/csvstaff2query.asp';
    }

    /**
     * @param $keyword
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetrieveException
     */
    public function getContent($keyword = null) : array
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                $this->baseUrl . '?name=' . urlencode($keyword) . '?' . time()
            );

            $fileContents = $response->getBody()->getContents();

            return str_getcsv($fileContents, "\n");
        } catch (Exception $e) {
            throw new DataSourceRetrieveException($e->getMessage(), $e->getCode());
        }
    }
}