<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Table(name="projects", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uqx__projects__follows", columns={"follows"})
 * }, indexes={
 *     @ORM\Index(name="fk__projects__area", columns={"area"})
 * })
 * @ORM\Entity
 */
class Project
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
     * @ORM\ManyToOne(targetEntity=Area::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="area", referencedColumnName="id", nullable=false)
     * })
     */
    private Area $area;

    /** @ORM\Column(name="title", type="string", length=255, nullable=false) */
    private string $title;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=false) */
    private \DateTimeInterface $updatedAt;

    /** @ORM\Column(name="notes", type="text", length=65535, nullable=true) */
    private ?string $notes;

    /** @ORM\Column(name="start_date", type="datetime", nullable=true) */
    private ?\DateTimeInterface $startDate;

    /** @ORM\Column(name="deadline", type="datetime", nullable=true) */
    private ?\DateTimeInterface $deadline;

    /** @ORM\Column(name="completed", type="datetime", nullable=true) */
    private ?\DateTimeInterface $completionDate;

    /**
     * @ORM\ManyToOne(targetEntity=Project::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="follows", referencedColumnName="id", nullable=true)
     * })
     */
    private ?Project $follows;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     * @ORM\JoinTable(name="project_tags", joinColumns={
     *     @ORM\JoinColumn(name="project", referencedColumnName="id", nullable=false)
     * }, inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag", referencedColumnName="id", nullable=false)
     * })
     * @var Collection<Tag>
     */
    private Collection $tags;

    public function __construct(Area $area, string $title)
    {
        $this->id = new Ulid;
        $this->area = $area;
        $this->title = $title;
        $this->createdAt = $this->updatedAt = $this->formatForDatabase(new \DateTime);
        $this->tags = new ArrayCollection;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getArea(): Area
    {
        return $this->area;
    }

    public function setArea(Area $area): void
    {
        $this->area = $area;
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function hasStartDate(): bool
    {
        return $this->startDate !== null;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate !== null
            ? $this->formatFromDatabase($this->startDate)
            : null;
    }

    public function setStartDate(?\DateTimeInterface $when): void
    {
        $this->startDate = $when !== null
            ? $this->formatForDatabase($when)
            : null;
    }

    public function hasDeadline(): bool
    {
        return $this->deadline !== null;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline !== null
            ? $this->formatFromDatabase($this->deadline)
            : null;
    }

    public function setDeadline(?\DateTimeInterface $deadline): void
    {
        $this->deadline = $deadline !== null
            ? $this->formatForDatabase($deadline)
            : null;
    }

    public function isCompleted(): bool
    {
        return $this->completionDate !== null;
    }

    public function getCompletionDate(): ?\DateTimeInterface
    {
        return $this->completionDate !== null
            ? $this->formatFromDatabase($this->completionDate)
            : null;
    }

    public function setCompletionDate(?\DateTimeInterface $completionDate): void
    {
        $this->completionDate = $completionDate !== null
            ? $this->formatForDatabase($completionDate)
            : null;
    }

    public function follows(): ?Project
    {
        return $this->follows;
    }

    public function follow(?Project $follows): void
    {
        $this->follows = $follows;
    }

    /** @return Collection<Tag> */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): void
    {
        $this->tags->add($tag);
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }
}
