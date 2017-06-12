<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Published_KKTIX
 *
 * @property string $url
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\Published_KKTIX whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\Published_KKTIX whereUrl($value)
 * @mixin \Eloquent
 */
class Published_KKTIX extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'url';

    protected $table = 'Published_KKTIX';

    protected $fillable = ['url', 'title'];
}
