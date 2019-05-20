<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedKKTIX extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'url';

    protected $table = 'Published_KKTIX';

    protected $fillable = ['url', 'title'];
}
