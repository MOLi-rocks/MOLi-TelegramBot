<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedNcdrRss extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $table = 'published_ncdr_rss';

    protected $fillable = ['id', 'category'];
}
