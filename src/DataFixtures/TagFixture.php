<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixture extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $tags = [];
        $tagCount = random_int(15, 25);
        for ($i = 0; $i < $tagCount; ++$i) {
            $tag = new Entity\Tag(bin2hex(random_bytes(random_int(3, 9))));
            $manager->persist($tag);
            $tags[] = $tag;
        }
        $manager->flush();

        /** @var \App\Entity\Project[] $projects */
        $projects = $manager->getRepository(Entity\Project::class)->findAll();
        foreach ($projects as $project) {
            if (!$this->probability(30)) {
                continue;
            }
            $projectTagKey = array_rand($tags);
            $project->addTag($tags[$projectTagKey]);
            $manager->persist($project);
        }
        $manager->flush();

        $tasks = $manager->getRepository(Entity\Task::class)->findAll();
        foreach ($tasks as $task) {
            if (!$this->probability(30)) {
                continue;
            }
            $taskTagKey = array_rand($tags);
            $task->addTag($tags[$taskTagKey]);
            $manager->persist($task);
        }
        $manager->flush();
    }

    public function getDependencies(): iterable
    {
        return [
            ProjectFixture::class,
            TaskFixture::class,
        ];
    }
}
