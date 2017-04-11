<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

class Published_NCNU_RSS extends Model
{
    public $timestamps = false;

    protected $table = 'Published_NCNU_RSS';

    protected $fillable = ['guid', 'title'];
}
