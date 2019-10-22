<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedNcnuRssRepository;
use MOLiBot\DataSources\Ncnu as DataSource;

class NcnuService
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
    public function getRss()
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

    /**
     * @param null $keyword
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStaffContact($keyword = NULL)
    {
        try {
            $contents = $this->dataSource->getStaffContact($keyword);

            $result = [];

            foreach ($contents as $content_item) {
                $tmpArray = [];

                $items = explode(",\"", $content_item);

                foreach ($items as $item) {
                    array_push($tmpArray, trim($item, "\"\r\n "));
                }

                array_push($result, $tmpArray);
            }

            return $result;
        } catch (\Exception $e) {
            return [];
        }
    }
}
