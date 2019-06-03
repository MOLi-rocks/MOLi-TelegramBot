<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedNcnuRss;

class PublishedNcnuRssRepository
{
    private $publishedNcnuRssModel;

    /**
     * PublishedNcnuRssRepository constructor.
     * @param PublishedNcnuRss $publishedNcnuRssModel
     */
    public function __construct(PublishedNcnuRss $publishedNcnuRssModel)
    {
        $this->publishedNcnuRssModel = $publishedNcnuRssModel;
    }

    public function checkRssPublished($guid)
    {
        return $this->publishedNcnuRssModel
            ->where('guid', '=', $guid)
            ->exists();
    }

    public function storePublishedRss($guid, $title)
    {
        return $this->publishedNcnuRssModel->create([
            'guid' => $guid,
            'title' => $title
        ]);
    }
}