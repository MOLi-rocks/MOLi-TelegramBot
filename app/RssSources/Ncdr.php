<?php

namespace MOLiBot\RssSources;

use MOLiBot\Exceptions\RssRetriveException;
use Exception;

class Ncdr extends Source
{
    protected $url = 'https://alerts.ncdr.nat.gov.tw/JSONAtomFeeds.ashx';

    /**
     * @return array|mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getContent()
    {
        try {
            $response = $this->httpClient->request('GET', $this->url);

            $fileContents = json_decode($response->getBody()->getContents(), true);

            if (!is_array($fileContents['entry'])) {
                $fileContents['entry'] = [$fileContents['entry']];
            }

            return $fileContents;
        } catch (Exception $e) {
            throw new RssRetriveException($e->getMessage(), $e->getCode());
        }
    }
}