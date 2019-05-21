<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedMOLiBlogArticle;

class PublishedMOLiBlogArticleRepository
{
    public function checkArticlePublished($articleId)
    {
        return PublishedMOLiBlogArticle::where('id', '=', $articleId)->exists();
    }

    public function storePublishedArticle($post)
    {
        return PublishedMOLiBlogArticle::create([
            'id' => $post->id,
            'uuid' => $post->uuid,
            'title' => $post->title
        ]);
    }
}