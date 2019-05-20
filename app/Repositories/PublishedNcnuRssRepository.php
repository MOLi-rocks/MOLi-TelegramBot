<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedNcnuRss;

class PublishedNcnuRssRepository
{
    public function checkRssPublished($guid)
    {
        return PublishedNcnuRss::where('guid', '=', $guid)->exists();
    }

    public function storePublishedRss($guid, $title)
    {
        return PublishedNcnuRss::create([
            'guid' => $guid,
            'title' => $title
        ]);
    }
}