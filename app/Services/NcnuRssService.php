<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedNcnuRssRepository;
use MOLiBot\RssSources\Ncnu as RssSource;

class NcnuRssService
{
    private $publishedNcnuRssRepository;
    private $rssSource;

    public function __construct(PublishedNcnuRssRepository $publishedNcnuRssRepository)
    {
        $this->publishedNcnuRssRepository = $publishedNcnuRssRepository;
        $this->rssSource = new RssSource();
    }

    /**
     * @return array|mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getNcnuRss()
    {
        return $this->rssSource->getContent();
    }

    public function checkRssPublished($guid)
    {
        return $this->publishedNcnuRssRepository->checkRssPublished($guid);
    }

    public function storePublishedRss($guid)
    {
        return $this->publishedNcnuRssRepository->storePublishedRss($guid);
    }
}
