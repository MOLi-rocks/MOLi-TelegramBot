<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetriveException;
use Exception;

class MoliKktix extends Source
{
    private $url;

    /**
     * MoliKktix constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = 'https://moli.kktix.cc/events.json';
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

            if (!isset($fileContents['entry'])) {
                throw new Exception('Entry Not Exist', 503);
            }

            if (!is_array($fileContents['entry'])) {
                $fileContents['entry'] = [$fileContents['entry']];
            }

            return $fileContents;
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }
}
