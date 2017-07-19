<?php

namespace OmniSynapse\CoreService\Job;

use GuzzleHttp\Psr7\Response;
use OmniSynapse\CoreService\Client;
use OmniSynapse\CoreService\Job;
use OmniSynapse\CoreService\Request\OfferForRedemption as OfferForRedemptionRequest;
use OmniSynapse\CoreService\Response\OfferForRedemption as OfferForRedemptionResponse;

// TODO: project models

/**
 * Class OfferRedemption
 * @package OmniSynapse\CoreService\Job
 */
class OfferRedemption extends Job
{
    /**
     * OfferRedemption constructor.
     * @param XXX $offer
     */
    public function __construct(XXX $offer)
    {
        parent::__construct();

        /** @var OfferForRedemptionRequest requestObject */
        $this->requestObject = (new OfferForRedemptionRequest())
            ->setId($offer->getId())
            ->setUserId($offer->getUserId());
    }

    /**
     * @return string
     */
    public function getHttpMethod() : string
    {
        return Client::METHOD_POST;
    }

    /**
     * @return string
     */
    public function getHttpPath() : string
    {
        return '/offers/'.$this->requestObject->id.'/redemption';
    }

    /**
     * @return \JsonSerializable
     */
    protected function getRequestObject() : \JsonSerializable
    {
        return $this->requestObject;
    }

    /**
     * @return string
     */
    protected function getResponseClass() : string
    {
        return OfferForRedemptionResponse::class;
    }

    /**
     * @param Response $response
     */
    public function handleError(Response $response)
    {
        // TODO: handler errors
    }
}