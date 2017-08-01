<?php

namespace OmniSynapse\CoreService\Response;

use Carbon\Carbon;

/**
 * Class SendNau
 * @package OmniSynapse\CoreService\Response
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Transaction
{
    /** @var string */
    public $transaction_id;

    /** @var string */
    public $source_account_id;

    /** @var string */
    public $destination_account_id;

    /** @var float */
    public $amount;

    /** @var string */
    public $status;

    /** @var string */
    public $created_at;

    /** @var string */
    public $type;

    /** @var array */
    public $feeTransactions;

    /**
     * @return string
     */
    public function getTransactionId() : string
    {
        return $this->transaction_id;
    }

    /**
     * @return string
     */
    public function getSourceAccountId() : string
    {
        return $this->source_account_id;
    }

    /**
     * @return string
     */
    public function getDestinationAccountId() : string
    {
        return $this->destination_account_id;
    }

    /**
     * @return float
     */
    public function getAmount() : float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getStatus() : string
    {
        return $this->status;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt() : Carbon
    {
        return Carbon::parse($this->created_at);
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getFeeTransactions() : array
    {
        return $this->feeTransactions;
    }
}
