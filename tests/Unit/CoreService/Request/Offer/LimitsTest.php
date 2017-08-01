<?php

namespace Tests\Unit\CoreService\Request\Offer;

use OmniSynapse\CoreService\Request\Offer\Limits;
use Tests\TestCase;
use Faker\Factory as Faker;

class LimitsTest extends TestCase
{
    /**
     * @return void
     */
    public function testGettersAndSetters()
    {
        $faker = Faker::create();

        $offers   = $faker->randomDigitNotNull;
        $perDay   = $faker->randomDigitNotNull;
        $perUser  = $faker->randomDigitNotNull;
        $minLevel = $faker->randomDigitNotNull;

        $limits = new Limits($offers, $perDay, $perUser, $minLevel);

        $this->assertTrue($offers === $limits->getOffers(), 'offers');
        $this->assertTrue($perDay === $limits->getPerDay(), 'perDay');
        $this->assertTrue($perUser === $limits->getPerUser(), 'perUser');
        $this->assertTrue($minLevel === $limits->getMinLevel(), 'minLevel');

        $expected = [
            'offers'    => $offers,
            'per_day'   => $perDay,
            'per_user'  => $perUser,
            'min_level' => $minLevel,
        ];

        $this->assertEquals($expected, $limits->jsonSerialize(), 'Expected array is not equals with LIMITS array');
    }
}
