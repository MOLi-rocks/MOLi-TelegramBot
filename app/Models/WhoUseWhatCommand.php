<?php

namespace MOLiBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MOLiBot\Models\WhoUseWhatCommand
 *
 * @property string $user-id
 * @property string $command
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WhoUseWhatCommand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WhoUseWhatCommand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WhoUseWhatCommand query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WhoUseWhatCommand whereCommand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\WhoUseWhatCommand whereUserId($value)
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
