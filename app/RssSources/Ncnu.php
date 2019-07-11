<?php

namespace MOLiBot\RssSources;

use MOLiBot\Exceptions\RssRetriveException;
use Exception;
use SoapBox\Formatter\Formatter;

class Ncnu extends Source
{
    protected $url = 'https://www.ncnu.edu.tw/ncnuweb/ann/RSS.aspx';

    /**
     * @return array|mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getContent()
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
            throw new RssRetriveException($e->getMessage(), $e->getCode());
        }
    }
}