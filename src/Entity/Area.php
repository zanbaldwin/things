<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Table(name="areas", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uqx__areas__follows", columns={"follows"})
 * })
 * @ORM\Entity
 */
class Area
{
    use DateTimeTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="ulid", nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private Ulid $id;

    /** @ORM\Column(name="title", type="string", length=255, nullable=false) */
    private string $title;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=false) */
    private \DateTimeInterface $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Area::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="follows", referencedColumnName="id", nullable=true)
     * })
     */
    private ?Area $follows;

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

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->createdAt);
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->updatedAt);
    }

    public function follows(): ?Area
    {
        return $this->follows;
    }

    public function follow(?Area $follows): void
    {
        $this->follows = $follows;
    }
}
