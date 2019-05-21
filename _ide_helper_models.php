<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace MOLiBot\Models{
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
 */
	class User extends \Eloquent {}
}

namespace MOLiBot\Models{
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
 */
	class MOLiBotApiToken extends \Eloquent {}
}

namespace MOLiBot\Models{
/**
 * MOLiBot\Models\PublishedNcdrRss
 *
 * @property string $id
 * @property string $category
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcdrRss whereId($value)
 */
	class PublishedNcdrRss extends \Eloquent {}
}

namespace MOLiBot\Models{
/**
 * MOLiBot\Models\FuelPrice
 *
 * @property string $name
 * @property string $unit
 * @property float $price
 * @property string $start_at
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\FuelPrice whereUnit($value)
 */
	class FuelPrice extends \Eloquent {}
}

namespace MOLiBot\Models{
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
 */
	class WhoUseWhatCommand extends \Eloquent {}
}

namespace MOLiBot\Models{
/**
 * MOLiBot\Models\PublishedKKTIX
 *
 * @property string $url
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedKKTIX whereUrl($value)
 */
	class PublishedKKTIX extends \Eloquent {}
}

namespace MOLiBot\Models{
/**
 * MOLiBot\Models\PublishedMOLiBlogArticle
 *
 * @property string $id
 * @property string $uuid
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedMOLiBlogArticle whereUuid($value)
 */
	class PublishedMOLiBlogArticle extends \Eloquent {}
}

namespace MOLiBot\Models{
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
 */
	class LINENotifyUser extends \Eloquent {}
}

namespace MOLiBot\Models{
/**
 * MOLiBot\Models\PublishedNcnuRss
 *
 * @property string $guid
 * @property string $title
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss query()
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\MOLiBot\Models\PublishedNcnuRss whereTitle($value)
 */
	class PublishedNcnuRss extends \Eloquent {}
}

