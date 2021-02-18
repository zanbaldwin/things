<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Table(name="checklists", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="uqx__checklists__follows", columns={"follows"})
 * })
 * @ORM\Entity
 */
class ChecklistItem
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
     * @ORM\ManyToOne(targetEntity=Task::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="task", referencedColumnName="id", nullable=false)
     * })
     */
    private Task $task;

    /** @ORM\Column(name="description", type="text", length=65535, nullable=false) */
    private string $description;

    /** @ORM\Column(name="created_at", type="datetime", nullable=false) */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(name="updated_at", type="datetime", nullable=false) */
    private \DateTimeInterface $updatedAt;

    /** @ORM\Column(name="completed", type="datetime", nullable=true) */
    private ?\DateTimeInterface $completionDate;

    /**
     * @ORM\ManyToOne(targetEntity=ChecklistItem::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="follows", referencedColumnName="id", nullable=true)
     * })
     */
    private ?ChecklistItem $follows;

    public function __construct(Task $task, string $description)
    {
        $this->id = new Ulid;
        $this->task = $task;
        $this->description = $description;
        $this->createdAt = $this->updatedAt = $this->formatForDatabase(new \DateTime);
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->createdAt);
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->formatFromDatabase($this->updatedAt);
    }

    public function isCompleted(): bool
    {
        return $this->completionDate !== null;
    }

    public function getCompletionDate(): ?\DateTimeInterface
    {
        return $this->completionDate !== null
            ? clone $this->completionDate
            : null;
    }

    public function setCompleted(?\DateTimeInterface $completionDate): void
    {
        $this->completionDate = $completionDate !== null
            ? clone $completionDate
            : null;
    }

    public function follows(): ?self
    {
        return $this->follows;
    }

    public function follow(?self $follows): void
    {
        $this->follows = $follows;
    }
}
