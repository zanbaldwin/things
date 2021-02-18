<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\HeadingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Table(name="headings", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uqx__headings__follows", columns={"follows"})
 * }, indexes={
 *     @ORM\Index(name="fk__headings__project", columns={"project"})
 * })
 * @ORM\Entity(repositoryClass=HeadingRepository::class)
 */
class Heading
{
    use DateTimeTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="ulid", nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     */
    private Ulid $id;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project", referencedColumnName="id", nullable=false)
     * })
     */
    private Project $project;

    /** @ORM\Column(name="title", type="text", length=255, nullable=false) */
    private string $title;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=false) */
    private \DateTimeInterface $updatedAt;

    /** @ORM\Column(name="archived", type="boolean", nullable=false) */
    private bool $archived = false;

    /**
     * @ORM\ManyToOne(targetEntity=Heading::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="follows", referencedColumnName="id", nullable=true)
     * })
     */
    private ?Heading $follows;

    public function __construct(Project $project, string $title)
    {
        $this->id = new Ulid;
        $this->project = $project;
        $this->title = $title;
        $this->createdAt = $this->updatedAt = $this->formatForDatabase(new \DateTime);
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project): void
    {
        $this->project = $project;
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

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): void
    {
        $this->archived = $archived;
    }

    public function follows(): ?Heading
    {
        return $this->follows;
    }

    public function follow(?Heading $follows): void
    {
        $this->follows = $follows;
    }
}
