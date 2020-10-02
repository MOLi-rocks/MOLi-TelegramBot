<?php

namespace MOLiBot\DataSources;

use MOLiBot\Exceptions\DataSourceRetrieveException;
use Exception;
use voku\helper\ASCII;

class MoliBlogArticle extends Source
{
    private $url;
    private $page = 1;

    /**
     * MoliBlogArticle constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->url = config('moli.blog.domain') . '/ghost/api/v2/content/posts/' .
            '?key=' . config('moli.blog.key') .
            '&include=authors,tags';
    }

    /**
     * @param $page
     * @return void
     */
    public function setPage($page) : void
    {
        if (!empty($page)) {
            $this->page = $page;
        }
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException|DataSourceRetrieveException
     */
    public function getContent() : array
    {
        try {
            $response = $this->httpClient->request('GET', $this->url . '&page=' . $this->page);

            $fileContents = json_decode($response->getBody()->getContents(), true);

            return $fileContents;
        } catch (Exception $e) {
            throw new DataSourceRetrieveException($e->getMessage(), $e->getCode());
        }
    }
}