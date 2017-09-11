<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Published_MOLi_Blog_Article
 *
 * @property string $id
 * @property string $uuid
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Published_MOLi_Blog_Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Published_MOLi_Blog_Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Published_MOLi_Blog_Article whereUuid($value)
 * @mixin \Eloquent
 */
class Published_MOLi_Blog_Article extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $table = 'Published_MOLi_blog_article';

    protected $fillable = ['id', 'uuid', 'title'];
}
