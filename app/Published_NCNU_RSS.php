<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Published_NCNU_RSS
 *
 * @property string $guid
 * @property string $title
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\Published_NCNU_RSS whereGuid($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\Published_NCNU_RSS whereTitle($value)
 * @mixin \Eloquent
 */
class Published_NCNU_RSS extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'guid';

    protected $table = 'Published_NCNU_RSS';

    protected $fillable = ['guid', 'title'];
}
