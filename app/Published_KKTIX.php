<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

class Published_KKTIX extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'url';

    protected $table = 'Published_KKTIX';

    protected $fillable = ['url', 'title'];
}
