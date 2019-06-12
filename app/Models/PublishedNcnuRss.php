<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\PublishedNcnuRss
 *
 * @property string $guid
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss whereGuid($value)
 * @mixin \Eloquent
 */
class PublishedNcnuRss extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'guid';

    protected $table = 'published_ncnu_rss';

    protected $fillable = ['guid'];
}
