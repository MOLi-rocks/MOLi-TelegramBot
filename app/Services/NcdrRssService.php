<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedNcdrRssRepository;
use MOLiBot\RssSources\Ncdr as RssSource;

class NcdrRssService
{
    private $publishedNcdrRssRepository;
    private $rssSource;

    public function __construct(PublishedNcdrRssRepository $publishedNcdrRssRepository)
    {
        $this->publishedNcdrRssRepository = $publishedNcdrRssRepository;
        $this->rssSource = new RssSource();
    }

    /**
     * @return array|mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNcdrRss()
    {
        return $this->rssSource->getContent();
    }

    public function checkRssPublished($id)
    {
        return $this->publishedNcdrRssRepository->checkRssPublished($id);
    }

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
