<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedKKTIXRepository;
use MOLiBot\DataSources\MoliKktix as DataSource;

class MOLiDayService
{
    private $publishedKKTIXRepository;
    private $dataSource;

    public function __construct(PublishedKKTIXRepository $publishedKKTIXRepository)
    {
        $this->publishedKKTIXRepository = $publishedKKTIXRepository;
        $this->dataSource = new DataSource();
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getEvents()
    {
        return $this->dataSource->getContent();
    }

    /**
     * @param $url string
     * @return bool
     */
    public function checkEventPublished($url)
    {
        return $this->publishedKKTIXRepository->checkEventPublished($url);
    }

    /**
     * @param $event array
     * @return \Illuminate\Database\Eloquent\Model|\MOLiBot\Models\PublishedKKTIX
     */
    public function storePublishedEvent($event)
    {
        return $this->publishedKKTIXRepository->storePublishedEvent($event);
    }
}