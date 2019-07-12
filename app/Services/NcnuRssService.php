<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedNcnuRssRepository;
use MOLiBot\DataSources\Ncnu as DataSource;

class NcnuRssService
{
    private $publishedNcnuRssRepository;
    private $dataSource;

    public function __construct(PublishedNcnuRssRepository $publishedNcnuRssRepository)
    {
        $this->publishedNcnuRssRepository = $publishedNcnuRssRepository;
        $this->dataSource = new DataSource();
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNcnuRss()
    {
        return $this->dataSource->getContent();
    }

    /**
     * @param $guid string
     * @return bool
     */
    public function checkRssPublished($guid)
    {
        return $this->publishedNcnuRssRepository->checkRssPublished($guid);
    }

    /**
     * @param $guid string
     * @return \Illuminate\Database\Eloquent\Model|PublishedNcnuRssRepository
     */
    public function storePublishedRss($guid)
    {
        return $this->publishedNcnuRssRepository->storePublishedRss($guid);
    }
}
