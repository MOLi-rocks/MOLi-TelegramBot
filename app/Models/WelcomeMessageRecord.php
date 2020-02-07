<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

class WelcomeMessageRecord extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'welcome_message_record';

    protected $fillable = ['chat_id', 'member_id', 'welcome_message_id', 'checked', 'join_at'];
}
