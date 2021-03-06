<?php

namespace App\Models;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Testimonial
 * @package App\Models
 *
 * @property string      id
 * @property string|null text
 * @property string      user_id
 * @property string      place_id
 * @property integer     stars
 * @property string      status
 * @property Carbon      created_at
 * @property Carbon      updated_at
 * @property User        user
 * @property Place       place
 * @property string      user_name
 * @property string      user_picture_url
 * @method Builder|Testimonial byPlace(Place $place)
 * @method Builder|Testimonial byPlaceAndUser(Place $place, User $user)
 */
class Testimonial extends Model
{
    use Uuids;

    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';
    const STATUS_INBOX    = 'inbox';

    /**
     * Testimonial constructor.
     *
     * @param array $attributes
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function __construct(array $attributes = [])
    {
        $this->connection = config('database.default');

        $this->table      = 'testimonials';
        $this->primaryKey = 'id';

        $this->initUuid();

        $this->casts = [
            'id'       => 'string',
            'user_id'  => 'string',
            'place_id' => 'string',
            'text'     => 'string',
            'stars'    => 'integer',
            'status'   => 'string',
        ];

        $this->fillable = [
            'user_id',
            'place_id',
            'text',
            'stars',
            'status',
        ];

        $this->hidden = [
            'user_id',
            'user',
        ];

        $this->appends = [
            'user_name',
            'user_picture_url'
        ];

        parent::__construct($attributes);
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('approved', function (Builder $builder) {
            $builder->where('status', self::STATUS_APPROVED);
        });
    }

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /** @return int */
    public function getStars(): int
    {
        return $this->stars;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function getUserPictureUrlAttribute()
    {
        return $this->user->picture_url;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }

    /**
     * @param Builder $builder
     * @param Place   $place
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByPlace(Builder $builder, Place $place)
    {
        return $builder->where('place_id', $place->getId());
    }

    /**
     * @param Builder $builder
     * @param Place   $place
     * @param User    $user
     *
     * @return Builder
     * @throws \InvalidArgumentException
     */
    public function scopeByPlaceAndUser(Builder $builder, Place $place, User $user)
    {
        return $this->scopeByPlace($builder, $place)->where('user_id', $user->getId());
    }

    /**
     * @return array
     */
    public static function getAllStatuses()
    {
        return [
            self::STATUS_APPROVED,
            self::STATUS_DECLINED,
            self::STATUS_INBOX,
        ];
    }
}
