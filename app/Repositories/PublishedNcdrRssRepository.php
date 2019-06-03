<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedNcdrRss;

class PublishedNcdrRssRepository
{
    private $publishedNcdrRssModel;

    /**
     * PublishedNcdrRssRepository constructor.
     * @param PublishedNcdrRss $publishedNcdrRssModel
     */
    public function __construct(PublishedNcdrRss $publishedNcdrRssModel)
    {
        $this->publishedNcdrRssModel = $publishedNcdrRssModel;
    }

    public function checkRssPublished($id)
    {
        return $this->publishedNcdrRssModel
            ->where('id', '=', $id)
            ->exists();
    }

    public function storePublishedRss($id, $category)
    {
        return $this->publishedNcdrRssModel->create([
            'id' => $id,
            'category' => $category
        ]);
    }
}