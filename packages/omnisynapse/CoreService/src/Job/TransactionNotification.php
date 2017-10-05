<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Transact;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\Request\TransactionNotification as TransactionNotificationRequest;
use OmniSynapse\CoreService\Response\Transaction;
use OmniSynapse\CoreService\FailedJob;

class TransactionNotification extends AbstractJob
{
    /** @var TransactionNotificationRequest */
    private $requestObject;

    /** @var Transact */
    private $transaction;

    /**
     * TransactionNotification constructor.
     *
     * @param Transact $transaction
     * @param string $category
     * @param CoreService $coreService
     */
    public function __construct(Transact $transaction, $category, CoreService $coreService)
    {
        parent::__construct($coreService);

        $this->transaction = $transaction;

        /** @var SendNau requestObject */
        $this->requestObject = (new TransactionNotificationRequest($transaction, $category));
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentProperties = parent::__sleep();
        return array_merge($parentProperties, ['requestObject', 'transaction']);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'POST';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/transactions/incoming';
    }

    /**
     * @return \JsonSerializable
     */
    public function getRequestObject(): \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    public function getResponseClass(): string
    {
        return Transaction::class;
    }

    /**
     * @param \Exception $exception
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\TransactionNotification($exception, $this->transaction);
    }
}
