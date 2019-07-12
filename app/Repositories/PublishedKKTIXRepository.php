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

    /**
     * @param $url string
     * @return bool
     */
    public function checkEventPublished($url)
    {
        return $this->publishedKKTIXModel
            ->where('url', '=', $url)
            ->exists();
    }

    /**
     * @param $event array
     * @return \Illuminate\Database\Eloquent\Model|PublishedKKTIX
     */
    public function storePublishedEvent($event)
    {
        return $this->publishedKKTIXModel->create([
            'url' => $event['url'],
            'title' => $event['title']
        ]);
    }
}