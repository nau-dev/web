<?php

namespace App\Models\User;

use App\Models\ActivationCode;
use App\Models\AdditionalField;
use App\Models\Identity;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\NauModels\User as CoreUser;
use App\Models\OfferData;
use App\Models\Place;
use App\Models\Role;
use App\Models\User;
use App\Models\OfferLink;
use Illuminate\Database\Eloquent\Relations;

/**
 * Trait RelationsTrait
 *
 * @package App\Models\User
 */
trait RelationsTrait
{
    /**
     * Get the referrer record associated with the user.
     *
     * @return Relations\BelongsTo
     */
    public function referrer(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user referrals.
     *
     * @return Relations\HasMany
     */
    public function referrals(): Relations\HasMany
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    /**
     * Get the user accounts relation
     *
     * @return Relations\HasMany
     */
    public function accounts(): Relations\HasMany
    {
        return $this->hasMany(Account::class, 'owner_id', 'id');
    }

    /**
     * @return Relations\HasMany
     */
    public function activationCodes(): Relations\HasMany
    {
        return $this->hasMany(ActivationCode::class);
    }

    /**
     * @return Relations\BelongsToMany
     */
    public function offers(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Offer::class, (new Redemption)->getTable())
                    ->withPivot([
                        'created_at',
                        'points',
                    ]);
    }

    /**
     * @return Relations\HasMany
     */
    public function offerLinks(): Relations\HasMany
    {
        return $this->hasMany(OfferLink::class);
    }

    /**
     * @return Relations\HasOne
     */
    public function coreUser(): Relations\HasOne
    {
        return $this->hasOne(CoreUser::class, 'id', 'id');
    }

    /**
     * @return Relations\HasOne
     */
    public function place(): Relations\HasOne
    {
        return $this->hasOne(Place::class, 'user_id', 'id');
    }

    /**
     * @return $this
     */
    public function additionalFields()
    {
        return $this->morphToMany(AdditionalField::class, 'parent', 'additional_field_values')->withPivot('value');
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    /**
     * @return mixed
     */
    public function parents(): Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_parents', 'user_id', 'parent_id');
    }

    /**
     * @return mixed
     */
    public function children(): Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_parents', 'parent_id', 'user_id');
    }

    /**
     * @return Relations\BelongsToMany
     */
    public function favoritePlaces(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Place::class, 'users_favorite_places');
    }

    /**
     * @return Relations\BelongsToMany
     */
    public function favoriteOffers()
    {
        return $this->belongsToMany(OfferData::class, 'users_favorite_offers', 'user_id', 'offer_id');
    }

    /**
     * @return Relations\BelongsToMany
     */
    public function identities()
    {
        return $this->hasMany(Identity::class);
    }
}
