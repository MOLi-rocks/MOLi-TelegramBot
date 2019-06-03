<?php

namespace MOLiBot\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * MOLiBot\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\User whereUpdatedAt($value)
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
