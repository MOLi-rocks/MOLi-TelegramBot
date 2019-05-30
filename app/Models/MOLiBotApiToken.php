<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

class MOLiBotApiToken extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $primaryKey = 'token';

    protected $table = 'bot_api_token';
    
    protected $dateFormat = Carbon::ISO8601;
    
    protected $fillable = ['token', 'user'];
    
    protected $dates = ['deleted_at'];
}
