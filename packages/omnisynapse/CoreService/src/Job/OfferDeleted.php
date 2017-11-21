<?php
/**
 * Created by PhpStorm.
 * User: skido
 * Date: 05.10.17
 * Time: 14:25
 */

namespace OmniSynapse\CoreService\Job;

use App\Models\NauModels\Offer;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\CoreService;
use OmniSynapse\CoreService\FailedJob;
use OmniSynapse\CoreService\Response\OfferDeleted as OfferDeletedResponse;

/**
 * Class OfferDeleted
 * @package OmniSynapse\CoreService\Job
 */
class OfferDeleted extends AbstractJob
{
    /** @var string */
    private $offer_id;

    /**
     * OfferUpdated constructor.
     *
     * @param Offer $offer
     * @param CoreService $coreService
     */
    public function __construct(Offer $offer, CoreService $coreService)
    {
        parent::__construct($coreService);

        $this->offer_id = $offer->id;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentProperties = parent::__sleep();
        return array_merge($parentProperties, ['offer_id']);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'DELETE';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/offers/'.$this->offer_id;
    }

    /**
     * @return null|\JsonSerializable
     */
    public function getRequestObject(): ?\JsonSerializable
    {
        return null;
    }

    /**
     * @return object
     */
    public function getResponseObject()
    {
        return new OfferDeletedResponse($this->offer_id);
    }

    /**
     * @param \Exception $exception
     * @return FailedJob
     */
    protected function getFailedResponseObject(\Exception $exception): FailedJob
    {
        return new FailedJob\OfferDeleted($exception, $this->offer_id);
    }
}
