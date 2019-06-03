<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\PublishedNcdrRss
 *
 * @property string $id
 * @property string $category
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss whereId($value)
 * @mixin \Eloquent
 */
class PublishedNcdrRss extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $table = 'published_ncdr_rss';

    protected $fillable = ['id', 'category'];
}
