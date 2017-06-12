<?php

namespace MOLiBot;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\WhoUseWhatCommand
 *
 * @property string $user-id
 * @property string $command
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\WhoUseWhatCommand whereCommand($value)
 * @method static \Illuminate\Database\Query\Builder|\MOLiBot\WhoUseWhatCommand whereUserId($value)
 * @mixin \Eloquent
 */
class WhoUseWhatCommand extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'user-id';

    protected $table = 'who_use_what_command';

    protected $fillable = ['user-id', 'command'];
}
