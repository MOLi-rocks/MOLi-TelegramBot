<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\PublishedKKTIX
 *
 * @property string $url
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX whereUrl($value)
 * @mixin \Eloquent
 */
class PublishedKKTIX extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'url';

    protected $table = 'published_moli_kktix';

    protected $fillable = ['url', 'title'];
}
