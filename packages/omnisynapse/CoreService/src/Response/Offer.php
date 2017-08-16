<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class OfferCreatedResponse
 * @package OmniSynapse\CoreService\Response
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Offer
{
    /** @var string */
    public $id;

    /** @var string */
    public $owner_id;

    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $category_id;

    /** @var Geo */
    public $geo;

    /** @var Limits */
    public $limits;

    /** @var float */
    public $reward;

    /** @var string */
    public $start_date;

    /** @var string */
    public $end_date;

    /** @var string */
    public $start_time;

    /** @var string */
    public $end_time;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->owner_id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->category_id;
    }

    /**
     * @return Geo
     */
    public function getGeo(): Geo
    {
        return $this->geo;
    }

    /**
     * @return Limits
     */
    public function getLimits(): Limits
    {
        return $this->limits;
    }

    /**
     * @return float
     */
    public function getReward(): float
    {
        return $this->reward;
    }

    /**
     * @return Carbon
     */
    public function getStartDate(): Carbon
    {
        return Carbon::parse($this->start_date);
    }

    /**
     * @return Carbon
     */
    public function getEndDate(): Carbon
    {
        return Carbon::parse($this->end_date);
    }

    /**
     * @return Carbon
     */
    public function getStartTime(): Carbon
    {
        return Carbon::parse($this->start_time);
    }

    /**
     * @return Carbon
     */
    public function getEndTime(): Carbon
    {
        return Carbon::parse($this->end_time);
    }
}