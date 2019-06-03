<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedKKTIX;

class PublishedKKTIXRepository
{
    private $publishedKKTIXModel;

    /**
     * PublishedKKTIXRepository constructor.
     * @param PublishedKKTIX $publishedKKTIXModel
     */
    public function __construct(PublishedKKTIX $publishedKKTIXModel)
    {
        $this->publishedKKTIXModel = $publishedKKTIXModel;
    }

    public function checkEventPublished($url)
    {
        return $this->publishedKKTIXModel
            ->where('url', '=', $url)
            ->exists();
    }

    public function storePublishedEvent($event)
    {
        return $this->publishedKKTIXModel->create([
            'url' => $event->url,
            'title' => $event->title
        ]);
    }
}