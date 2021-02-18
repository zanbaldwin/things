<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Table(name="tags", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uqx__tags__name", columns={"title"})
 * })
 * @ORM\Entity
 */
class Tag
{
    use DateTimeTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="ulid", nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private Ulid $id;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=false) */
    private \DateTimeInterface $updatedAt;

    /** @ORM\Column(name="title", type="string", length=255, nullable=false) */
    private string $title;

    public function __construct(string $title)
    {
        $this->id = new Ulid;
        $this->title = $title;
        $this->createdAt = $this->formatForDatabase(new \DateTime);
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->createdAt);
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->updatedAt);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
}
