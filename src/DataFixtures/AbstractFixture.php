<?php declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;

abstract class AbstractFixture extends Fixture
{
    protected function probability(int $percentage): bool
    {
        return random_int(0, 100) > (100 - $percentage);
    }
}
