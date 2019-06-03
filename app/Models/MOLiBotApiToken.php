<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

/**
 * MOLiBot\Models\MOLiBotApiToken
 *
 * @property string $token
 * @property string $user
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken newQuery()
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\Models\MOLiBotApiToken onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\MOLiBotApiToken whereUser($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\Models\MOLiBotApiToken withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\Models\MOLiBotApiToken withoutTrashed()
 * @mixin \Eloquent
 */
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
