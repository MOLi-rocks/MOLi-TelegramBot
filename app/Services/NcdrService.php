<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedNcdrRssRepository;
use MOLiBot\DataSources\NcdrRss as RssDataSource;
use MOLiBot\DataSources\NcdrStopWorkingInfo as StopWorkingInfoDataSource;

class NcdrService
{
    private $publishedNcdrRssRepository;
    private $rssDataSource;
    private $stopWorkingInfoSource;

    /**
     * NcdrService constructor.
     * @param PublishedNcdrRssRepository $publishedNcdrRssRepository
     * @param RssDataSource $rssDataSource
     * @param StopWorkingInfoDataSource $stopWorkingInfoDataSource
     */
    public function __construct(PublishedNcdrRssRepository $publishedNcdrRssRepository,
                                RssDataSource $rssDataSource,
                                StopWorkingInfoDataSource $stopWorkingInfoDataSource
    )
    {
        $this->publishedNcdrRssRepository = $publishedNcdrRssRepository;
        $this->rssDataSource = $rssDataSource;
        $this->stopWorkingInfoSource = $stopWorkingInfoDataSource;
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRss()
    {
        return $this->rssDataSource->getContent();
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

    /**
     * status:
     * 1 => 成功
     * 0 => 失敗
     * -1 => 錯誤
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStopWorkingInfo()
    {
        try {
            $data = $this->stopWorkingInfoSource->getContent();

            if ($data['success'] === false || count($data['result']) < 1) {
                return ['status' => 0, 'data' => ''];
            } else {
                $output_str = '';

                foreach ($data['result'] as $result) {
                    $output_str .= trim($result['description']) . PHP_EOL;
                }

                return ['status' => 1, 'data' => $output_str];
            }
        } catch (\Exception $e) {
            return ['status' => -1, 'data' => ''];
        }
    }
}
