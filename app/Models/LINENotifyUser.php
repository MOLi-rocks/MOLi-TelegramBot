<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;
use MOLiBot\Http\Controllers\LINENotifyController;

class LINENotifyUser extends Model
{
    protected $table = 'line_notify_users';

    protected $fillable = ['access_token', 'targetType', 'target', 'sid', 'email'];
}
