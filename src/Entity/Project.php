<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;

#[ORM\Table(name: 'projects')]
#[ORM\UniqueConstraint(name: 'uqx__projects__follows', columns: ['follows'])]
#[ORM\Index(columns: ['area'], name: 'fk__projects__area')]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    use DateTimeTrait;

    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'ulid', nullable: false)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UlidGenerator::class)]
    private Ulid $id;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private \DateTimeInterface $updatedAt;

    #[ORM\Column(name: 'notes', type: 'text', length: 65535, nullable: true)]
    private ?string $notes;

    #[ORM\Column(name: 'start_date', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $startDate;

    #[ORM\Column(name: 'deadline', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $deadline;

    #[ORM\Column(name: 'completed', type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $completionDate;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(name: 'follows', referencedColumnName: 'id', nullable: true)]
    private ?Project $follows;

    /** @var Collection<Tag> */
    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'project_tags')]
    #[ORM\JoinColumn(name: 'project', referencedColumnName: 'id', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'tag', referencedColumnName: 'id', nullable: false)]
    private Collection $tags;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Area::class)]
        #[ORM\JoinColumn(name: 'area', referencedColumnName: 'id', nullable: false)]
        private Area $area,
        #[ORM\Column(name: 'title', type: 'string', length: 255, nullable: false)]
        private string $title
    ) {
        $this->id = new Ulid;
        $this->createdAt = $this->formatForDatabase(new \DateTime);
        $this->updatedAt = $this->createdAt;
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
