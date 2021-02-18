<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class HeadingFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\Project[] $areas */
        $projects = $manager->getRepository(Entity\Project::class)->findAll();
        foreach ($projects as $project) {
            $previous = null;
            $headingCount = random_int(2, 5);
            for ($i = 0; $i < $headingCount; $i++) {
                $heading = new Entity\Heading($project, bin2hex(random_bytes(random_int(9, 12))));
                $heading->follow($previous);
                $this->probability(5) && $heading->setArchived(true);
                $previous = $heading;
                $manager->persist($heading);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            ProjectFixture::class,
        ];
    }
}
