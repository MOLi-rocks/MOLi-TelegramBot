<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\WelcomeMessageRecord
 *
 * @property int $chat_id
 * @property int $member_id new member id
 * @property int $welcome_message_id
 * @property int $checked
 * @property int $join_at unix-timestamp of join time
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord whereJoinAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WelcomeMessageRecord whereWelcomeMessageId($value)
 * @mixin \Eloquent
 */
class WelcomeMessageRecord extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $table = 'welcome_message_record';

    protected $fillable = ['chat_id', 'member_id', 'welcome_message_id', 'checked', 'join_at'];
}
