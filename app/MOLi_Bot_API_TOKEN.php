<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Carbon\Carbon;

/**
 * MOLiBot\MOLi_Bot_API_TOKEN
 *
 * @property string $token
 * @property string $user
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\MOLi_Bot_API_TOKEN whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\MOLi_Bot_API_TOKEN whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\MOLi_Bot_API_TOKEN whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\MOLi_Bot_API_TOKEN whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\MOLi_Bot_API_TOKEN whereUser($value)
 * @mixin \Eloquent
 */
class MOLi_Bot_API_TOKEN extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    protected $primaryKey = 'token';

    protected $table = 'API_TOKEN';
    
    protected $dateFormat = Carbon::ISO8601;
    
    protected $fillable = ['token', 'user'];
    
    protected $dates = ['deleted_at'];
}
