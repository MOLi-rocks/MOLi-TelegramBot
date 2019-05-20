<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

class PublishedMOLiBlogArticle extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $table = 'Published_MOLi_blog_article';

    protected $fillable = ['id', 'uuid', 'title'];
}