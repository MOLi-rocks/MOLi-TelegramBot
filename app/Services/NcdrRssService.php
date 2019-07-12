<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedNcdrRssRepository;
use MOLiBot\DataSources\Ncdr as DataSource;

class NcdrRssService
{
    private $publishedNcdrRssRepository;
    private $dataSource;

    public function __construct(PublishedNcdrRssRepository $publishedNcdrRssRepository)
    {
        $this->publishedNcdrRssRepository = $publishedNcdrRssRepository;
        $this->dataSource = new DataSource();
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNcdrRss()
    {
        return $this->dataSource->getContent();
    }

    /**
     * @param $id string
     * @return bool
     */
    public function checkRssPublished($id)
    {
        return $this->publishedNcdrRssRepository->checkRssPublished($id);
    }

    /**
     * @param $id string
     * @param $category string
     * @return \Illuminate\Database\Eloquent\Model|PublishedNcdrRssRepository
     */
    public function storePublishedRss($id, $category)
    {
        return $this->publishedNcdrRssRepository->storePublishedRss($id, $category);
    }

    /**
     * @param array $excludeId
     * @return boolean
     */
    public function deletePublishedRecordWithExcludeId($excludeId)
    {
        return $this->publishedNcdrRssRepository->deletePublishedRecordWithExcludeId($excludeId);
    }
}
