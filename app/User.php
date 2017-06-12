<?php

namespace MOLiBot;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * MOLiBot\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
}
