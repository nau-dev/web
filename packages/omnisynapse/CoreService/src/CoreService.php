<?php

namespace OmniSynapse\CoreService;

use App\Events\UserEvent;
use App\Models\NauModels\Account;
use App\Models\NauModels\Offer;
use App\Models\NauModels\Redemption;
use App\Models\NauModels\Transact;
use App\Models\User;
use GuzzleHttp\Client;
use OmniSynapse\CoreService\Job\EventOccurred;

interface CoreService
{
    /**
     * @param Client $client
     * @return CoreService
     */
    public function setClient(Client $client): CoreService;

    /**
     * @param Offer $offer
     * @return AbstractJob
     */
    public function offerCreated(Offer $offer): AbstractJob;

    /**
     * @param Redemption $redemption
     * @return AbstractJob
     */
    public function offerRedemption(Redemption $redemption): AbstractJob;

    /**
     * @param Offer $offer
     * @return AbstractJob
     */
    public function offerUpdated(Offer $offer): AbstractJob;

    /**
     * @param Transact $transaction
     * @return AbstractJob
     */
    public function sendNau(Transact $transaction): AbstractJob;

    /**
     * @param User $user
     * @return AbstractJob
     */
    public function userCreated(User $user): AbstractJob;

    /**
     * @param Transact $transaction
     * @param string $category
     * @return AbstractJob
     */
    public function transactionNotification(Transact $transaction, $category): AbstractJob;

    /**
     * @param Offer $offer
     *
     * @return AbstractJob
     */
    public function offerDeleted(Offer $offer): AbstractJob;

    public function crossChange(Account $account, string $ethAddress, float $amount, bool $isIncoming): AbstractJob;

    /**
     * @param UserEvent $event
     *
     * @return EventOccurred
     */
    public function eventOccurred(UserEvent $event): EventOccurred;

    public function getClient(): Client;
}
