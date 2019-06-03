<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedMOLiBlogArticle;

class PublishedMOLiBlogArticleRepository
{
    private $publishedMOLiBlogArticleModel;

    /**
     * PublishedMOLiBlogArticleRepository constructor.
     * @param PublishedMOLiBlogArticle $publishedMOLiBlogArticleModel
     */
    public function __construct(PublishedMOLiBlogArticle $publishedMOLiBlogArticleModel)
    {
        $this->publishedMOLiBlogArticleModel = $publishedMOLiBlogArticleModel;
    }

    public function checkArticlePublished($articleId)
    {
        return $this->publishedMOLiBlogArticleModel
            ->where('id', '=', $articleId)
            ->exists();
    }

    public function storePublishedArticle($post)
    {
        return $this->publishedMOLiBlogArticleModel->create([
            'id' => $post->id,
            'uuid' => $post->uuid,
            'title' => $post->title
        ]);
    }
}