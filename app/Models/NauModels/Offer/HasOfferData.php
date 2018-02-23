<?php

namespace App\Models\NauModels\Offer;

use App\Models\OfferData;

/**
 * Trait OfferData
 * @package App\Models\NauModels\Offer
 *
 * @property OfferData offerData
 */
trait HasOfferData
{
    public function offerData()
    {
        return $this->hasOne(OfferData::class, 'id', 'id')
                    ->withDefault(function ($model) {
                        $model->id = $this->getId();
                    });
    }

    /**
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getDeliveryAttribute(): bool
    {
        return $this->offerData->delivery;
    }

    /**
     * @return null|string
     */
    public function getTypeAttribute(): ?string
    {
        return $this->offerData->type;
    }

    /**
     * @return null|string
     */
    public function getGiftBonusDescrAttribute(): ?string
    {
        return $this->offerData->gift_bonus_descr;
    }

    /**
     * @return float|null
     */
    public function getDiscountPercentAttribute(): ?float
    {
        return $this->offerData->discount_percent;
    }

    /**
     * @return float|null
     */
    public function getDiscountStartPriceAttribute(): ?float
    {
        return $this->offerData->discount_start_price;
    }

    /**
     * @return float|null
     */
    public function getDiscountFinishPriceAttribute(): ?float
    {
        return $this->offerData->discount_finish_price;
    }

    /**
     * @return null|string
     */
    public function getCurrencyAttribute(): ?string
    {
        return $this->offerData->currency;
    }
}