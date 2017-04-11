<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

class Published_KKTIX extends Model
{
    protected $table = 'Published_KKTIX';

    protected $fillable = ['url', 'title'];
}
