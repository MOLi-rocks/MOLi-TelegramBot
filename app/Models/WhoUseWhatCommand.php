<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

class WhoUseWhatCommand extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'user-id';

    protected $table = 'who_use_what_command';

    protected $fillable = ['user-id', 'command'];
}
