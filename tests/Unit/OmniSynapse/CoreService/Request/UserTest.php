<?php

namespace OmniSynapse\CoreService\Request;

use App\Models\Role;
use Faker\Factory as Faker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
    {
        $faker = Faker::create();

        /*
         * Prepare params
         */
        $userId      = $faker->uuid;
        $name        = $faker->name;
        $referrerId  = $faker->uuid;
        $defaultRole = Role::ROLE_ADMIN;

        /*
         * Referrer relation
         */
        $referrer = $this->createMock(\App\Models\User::class);
        $referrer->method('getId')->willReturn($referrerId);

        /*
         * Prepare User mock
         */
        $user = $this->createMock(\App\Models\User::class);

        /*
         * Set User methods
         */
        $user->method('getId')->willReturn($userId);
        $user->method('getName')->willReturn($name);
        $user->method('getReferrer')->willReturn($referrer);
        $user->method('isAdmin')->willReturn(true);

        /*
         * Create User request and prepare jsonSerialize for comparing
         */
        $userCreatedRequest = new User($user);
        $expected           = [
            'id'           => $userId,
            'username'     => $name,
            'referrer_id'  => $referrerId,
            'default_role' => $defaultRole,
        ];

        /*
         * Compare arrays
         */
        $this->assertEquals($expected, $userCreatedRequest->jsonSerialize(),
            'Expected array is not equals with userCreated array');
    }
}
