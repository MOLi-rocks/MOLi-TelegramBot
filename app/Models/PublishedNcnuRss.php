<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedNcnuRss extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'guid';

    protected $table = 'published_ncnu_rss';

    protected $fillable = ['guid', 'title'];
}
