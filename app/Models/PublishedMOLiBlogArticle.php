<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\PublishedMOLiBlogArticle
 *
 * @property string $id
 * @property string $uuid
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle whereUuid($value)
 * @mixin \Eloquent
 */
class PublishedMOLiBlogArticle extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'id';

    protected $table = 'published_moli_blog_article';

    protected $fillable = ['id', 'uuid', 'title'];
}
