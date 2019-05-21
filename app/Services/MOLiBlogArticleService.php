<?php

namespace MOLiBot\Services;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\TransferException as GuzzleHttpTransferException;
use MOLiBot\Repositories\PublishedMOLiBlogArticleRepository;

class MOLiBlogArticleService
{
    private $publishedMOLiBlogArticleRepository;

    public function __construct(PublishedMOLiBlogArticleRepository $publishedMOLiBlogArticleRepository)
    {
        $this->publishedMOLiBlogArticleRepository = $publishedMOLiBlogArticleRepository;
    }

    public function getMOLiBlogArticles($limit = 1)
    {
        $MOLi_blog_api = env('MOLi_BLOG_URL') . '/ghost/api/v0.1/posts/' .
            '?client_id=' . env('MOLi_BLOG_CLIENT_ID') .
            '&client_secret=' . env('MOLi_BLOG_CLIENT_SECRET') .
            '&include=author,tags' .
            '&limit=' . $limit;

        $client = new GuzzleHttpClient();

        try {
            $fileContents = $client->request('GET', $MOLi_blog_api, [
                'headers' => [
                    'User-Agent' => 'MOLi Bot',
                    'Accept' => 'application/json'
                ],
                'timeout' => 10
            ]);
        } catch (GuzzleHttpTransferException $e) {
            $fileContents = '';
        }

        return $fileContents;
    }

    public function checkArticlePublished($articleId)
    {
        return $this->publishedMOLiBlogArticleRepository->checkArticlePublished($articleId);
    }

    public function storePublishedArticle($post)
    {
        return $this->publishedMOLiBlogArticleRepository->storePublishedArticle($post);
    }
}