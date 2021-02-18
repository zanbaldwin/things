<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\Area[] $areas */
        $areas = $manager->getRepository(Entity\Area::class)->findAll();
        foreach ($areas as $area) {
            $previous = null;
            $projectCount = random_int(4, 7);
            for ($i = 0; $i < $projectCount; $i++) {
                $project = new Entity\Project($area, bin2hex(random_bytes(random_int(7, 10))));
                $project->follow($previous);
                $this->probability(50) && $project->setNotes(bin2hex(random_bytes(random_int(5, 20))));
                $this->probability(7) && $project->setCompletionDate(new \DateTime);
                $duration = sprintf('P%dW%dDT%dH%dM', random_int(0, 2), random_int(0, 7), random_int(0, 18), random_int(0, 59));
                $this->probability(15) && $project->setStartDate((new \DateTime)->add(new \DateInterval($duration)));
                $duration = sprintf('P%dW%dDT%dH%dM', random_int(2, 5), random_int(0, 7), random_int(0, 18), random_int(0, 59));
                $this->probability(30) && $project->setDeadline((new \DateTime)->add(new \DateInterval($duration)));
                $previous = $project;
                $manager->persist($project);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            AreaFixture::class,
        ];
    }
}
