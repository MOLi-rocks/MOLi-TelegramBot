<?php

namespace MOLiBot\Repositories;

use MOLiBot\Models\PublishedNcdrRss;

class PublishedNcdrRssRepository
{
    public function checkRssPublished($id)
    {
        return PublishedNcdrRss::where('id', '=', $id)->exists();
    }

    public function storePublishedRss($id, $category)
    {
        return PublishedNcdrRss::create([
            'id' => $id,
            'category' => $category
        ]);
    }
}