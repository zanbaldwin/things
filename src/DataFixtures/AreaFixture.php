<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Area;
use Doctrine\Persistence\ObjectManager;

class AreaFixture extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        $previous = null;
        for ($i = 0; $i < 5; $i++) {
            $area = new Area(bin2hex(random_bytes(10)));
            $area->follow($previous);
            $manager->persist($area);
            $previous = $area;
        }
        $manager->flush();
    }
}
