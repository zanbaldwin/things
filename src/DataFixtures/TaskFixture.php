<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\Heading[] $headings */
        $headings = $manager->getRepository(Entity\Heading::class)->findAll();
        foreach ($headings as $heading) {
            /** @var \App\Entity\Task|null $previous */
            $previous = null;
            $taskCount = random_int(2, 4);
            for ($i = 0; $i < $taskCount; $i++) {
                $task = new Entity\Task($heading->getProject(), $heading, bin2hex(random_bytes(12)));
                $task->follow($previous);
                $task->setNotes(bin2hex(random_bytes(20)));
                $this->probability(10) && $task->setCompletionDate(new \DateTime);
                $duration = sprintf('P%dW%dDT%dH%dM', random_int(0, 2), random_int(0, 7), random_int(0, 18), random_int(0, 59));
                $this->probability(25) && $task->setStartDate((new \DateTime)->add(new \DateInterval($duration)));
                $duration = sprintf('P%dW%dDT%dH%dM', random_int(2, 5), random_int(0, 7), random_int(0, 18), random_int(0, 59));
                $this->probability(40) && $task->setDeadline((new \DateTime)->add(new \DateInterval($duration)));
                $previous = $task;
                $manager->persist($task);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            HeadingFixture::class,
        ];
    }
}
