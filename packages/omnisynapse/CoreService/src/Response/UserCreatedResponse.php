<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class UserCreatedResponse
 * @package OmniSynapse\CoreService\Response
 *
 * @property string id
 * @property string username
 * @property string referrer_id
 * @property int level
 * @property int points
 * @property array wallets
 * @property Carbon created_at
 */
class UserCreatedResponse
{
    /** @var string */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $referrer_id;

    /** @var int */
    public $level;

    /** @var int */
    public $points;

    /** @var array */
    public $wallets;

    /** @var Carbon */
    public $created_at;

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getReferrerId() : string
    {
        return $this->referrer_id;
    }

    /**
     * @return array
     */
    public function getWallets() : array
    {
        return $this->wallets;
    }

    /**
     * @return int
     */
    public function getLevel() : int
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getPoints() : int
    {
        return $this->points;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt() : Carbon
    {
        return Carbon::parse($this->created_at);
    }
}