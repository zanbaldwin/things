<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Table(name="tasks", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uqx__tasks__follows", columns={"follows"})
 * }, indexes={
 *     @ORM\Index(name="fk__tasks__heading", columns={"heading"}),
 *     @ORM\Index(name="fk__tasks__project", columns={"project"})
 * })
 * @ORM\Entity
 */
class Task
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

    /**
     * @ORM\ManyToOne(targetEntity=Heading::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="heading", referencedColumnName="id", nullable=true)
     * })
     */
    private ?Heading $heading;

    /** @ORM\Column(name="title", type="string", length=255, nullable=false) */
    private string $title;

    /** @ORM\Column(name="notes", type="text", length=65535, nullable=true) */
    private ?string $notes;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=false) */
    private \DateTimeInterface $updatedAt;

    /** @ORM\Column(name="start_date", type="datetime", nullable=true) */
    private ?\DateTimeInterface $startDate;

    /** @ORM\Column(name="deadline", type="datetime", nullable=true) */
    private ?\DateTimeInterface $deadline;

    /** @ORM\Column(name="completed", type="datetime", nullable=true) */
    private ?\DateTimeInterface $completionDate;

    /**
     * @ORM\ManyToOne(targetEntity=Task::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="follows", referencedColumnName="id", nullable=true)
     * })
     */
    private ?Task $follows;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class)
     * @ORM\JoinTable(name="task_tags", joinColumns={
     *     @ORM\JoinColumn(name="task", referencedColumnName="id", nullable=false)
     * }, inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag", referencedColumnName="id", nullable=false)
     * })
     * @var Collection<Tag>
     */
    private Collection $tags;

    public function __construct(Project $project, ?Heading $heading, string $title)
    {
        $this->id = new Ulid;
        $this->project = $project;
        $this->heading = $heading;
        $this->title = $title;
        $this->createdAt = $this->updatedAt = $this->formatForDatabase(new \DateTime);
        $this->tag = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function setProject(Project $project, ?Heading $heading = null): void
    {
        $this->project = $project;
        $this->heading = $heading;
    }

    public function getHeading(): ?Heading
    {
        return $this->heading;
    }

    public function setHeading(?Heading $heading): void
    {
        $this->heading = $heading;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): void
    {
        $this->notes = $notes;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->createdAt);
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->updatedAt);
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

    public function setStartDate(?\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate !== null
            ? $this->formatForDatabase($startDate)
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

    public function follows(): ?Task
    {
        return $this->follows;
    }

    public function follow(?Task $follows): void
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
