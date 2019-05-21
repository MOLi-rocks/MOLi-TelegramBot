<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedKKTIX;

class PublishedKKTIXRepository
{
    public function checkEventPublished($url)
    {
        return PublishedKKTIX::where('url', '=', $url)->exists();
    }

    public function storePublishedEvent($event)
    {
        return PublishedKKTIX::create([
            'url' => $event->url,
            'title' => $event->title
        ]);
    }
}