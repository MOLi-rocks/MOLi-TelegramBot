<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\LINENotifyUser
 *
 * @property int $id
 * @property string $access_token
 * @property string|null $targetType
 * @property string|null $target
 * @property string|null $sid
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $status
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereSid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\LINENotifyUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LINENotifyUser extends Model
{
    protected $table = 'line_notify_users';

    protected $fillable = ['access_token', 'targetType', 'target', 'sid', 'email'];
}
