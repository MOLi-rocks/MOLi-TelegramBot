<?php

namespace MOLiBot\Services;

use MOLiBot\Repositories\PublishedMOLiBlogArticleRepository;
use MOLiBot\DataSources\MoliBlogArticle as DataSource;

class MOLiBlogArticleService
{
    private $publishedMOLiBlogArticleRepository;
    private $dataSource;

    public function __construct(PublishedMOLiBlogArticleRepository $publishedMOLiBlogArticleRepository)
    {
        $this->publishedMOLiBlogArticleRepository = $publishedMOLiBlogArticleRepository;
        $this->dataSource = new DataSource();
    }

    /**
     * @param int $page
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMOLiBlogArticles($page = 1)
    {
        $this->dataSource->setPage($page);

        return $this->dataSource->getContent();
    }

    /**
     * @param $articleId
     * @return bool
     */
    public function checkArticlePublished($articleId)
    {
        return $this->publishedMOLiBlogArticleRepository->checkArticlePublished($articleId);
    }

    /**
     * @param $post
     * @return \Illuminate\Database\Eloquent\Model|PublishedMOLiBlogArticleRepository
     */
    public function storePublishedArticle($post)
    {
        return $this->publishedMOLiBlogArticleRepository->storePublishedArticle($post);
    }
}