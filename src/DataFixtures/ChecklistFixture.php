<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ChecklistFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var \App\Entity\Task[] $tasks */
        $tasks = $manager->getRepository(Entity\Task::class)->findAll();
        foreach ($tasks as $task) {
            $previous = null;
            if (!$this->probability(20)) {
                continue;
            }
            $itemCount = random_int(1, 5);
            for ($i = 0; $i < $itemCount; ++$i) {
                $item = new Entity\ChecklistItem($task, bin2hex(random_bytes(random_int(3, 9))));
                $item->follow($previous);
                $item->setCompleted($this->probability(10) ? new \DateTime : null);
                $manager->persist($item);
                $previous = $item;
            }
        }
        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            TaskFixture::class,
        ];
    }
}
