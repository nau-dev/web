<?php

namespace OmniSynapse\CoreService\Job;

use App\Models\User;
use OmniSynapse\CoreService\AbstractJob;
use OmniSynapse\CoreService\Mail\UserCreatedFail;
use OmniSynapse\CoreService\Request\User as UserRequest;
use OmniSynapse\CoreService\Response\User as UserResponse;

/**
 * Class UserCreated
 * @package OmniSynapse\CoreService\Job
 */
class UserCreated extends AbstractJob
{
    /** @var UserRequest */
    private $requestObject;

    /** @var User */
    private $user;

    /**
     * UserCreated constructor.
     *
     * @param User $user
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(User $user, \GuzzleHttp\Client $client)
    {
        parent::__construct($client);

        $this->user = $user;

        /** @var UserRequest requestObject */
        $this->requestObject = new UserRequest($user);
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return 'PUT';
    }

    /**
     * @return string
     */
    public function getHttpPath(): string
    {
        return '/user';
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
        return UserResponse::class;
    }

    /**
     * @return void
     */
    public function failed(): void
    {
        \Mail::to($this->user->getEmail())
            ->queue(new UserCreatedFail($this->user));
    }
}
