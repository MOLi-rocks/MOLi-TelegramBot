<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetriveException;
use Exception;

class CPCProductPrice extends Source
{
    private $url;
    private $historyUrl;
    private $historyProdId = 1;

    /**
     * MoliKktix constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = 'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx/getCPCMainProdListPrice';

        $this->historyUrl = 'https://vipmember.tmtd.cpc.com.tw/OpenData/ListPriceWebService.asmx/getCPCMainProdListPrice_Historical';
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetriveException
     */
    public function getContent() : array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $prodId
     * @return void
     */
    public function setHistoryProdId($prodId) : void
    {
        if (!empty($prodId)) {
            $this->historyProdId = $prodId;
        }
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetriveException
     */
    public function getHistoryContent() : array
    {
        /*
        $prodId = array(
            '1' => '92無鉛汽油',
            '2' => '95無鉛汽油',
            '3' => '98無鉛汽油',
            '4' => '超級/高級柴油',
            '5' => '低硫燃料油(0.5%)',
            '6' => '甲種低硫燃料油(0.5)'
        );
        */

        try {
            $response = $this->httpClient->request('GET', $this->historyUrl . '?prodid=' . $this->historyProdId);

            return $this->handleResponse($response);
        } catch (Exception $e) {
            throw new DataSourceRetriveException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param $response
     * @return array
     */
    private function handleResponse($response) : array
    {
        $fileContents = $response->getBody()->getContents();

        // SOAP response to regular XML
        $xml = preg_replace('/(<\/?)(\w+):([^>]*>)/', '$1$2$3', $fileContents);

        $simpleXml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        $json = json_decode(json_encode($simpleXml), 1);

        return $json['diffgrdiffgram']['NewDataSet']['tbTable'];
    }
}