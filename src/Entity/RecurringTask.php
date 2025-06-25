<?php

namespace App\Entity;

use App\Repository\RecurringTaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Order;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecurringTaskRepository::class)]
class RecurringTask
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $task = null;

    #[ORM\Column(nullable: true)]
    private ?int $frequency = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frequencyUnit = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $nextTime = null;

    private ?string $nextTimeToStr = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $nbDaysBeforeToDisplay = 0;

    private ?\DateTime $dateToDisplay = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?bool $canProcrastinate = false;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $end = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?bool $latestOperationsVisible = false;

    /**
     * @var Collection<int, Log>
     */
    #[ORM\OneToMany(targetEntity: Log::class, mappedBy: 'recurringTask', orphanRemoval: true)]
    private Collection $logs;

    /**
     * @var Collection<int, LinkedFile>
     */
    #[ORM\OneToMany(targetEntity: LinkedFile::class, mappedBy: 'recurringTask', orphanRemoval: true)]
    private Collection $linkedFiles;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $procrastinated = null;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
        $this->linkedFiles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->task;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?string
    {
        return $this->task;
    }

    public function setTask(string $task): static
    {
        $this->task = $task;

        return $this;
    }

    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(?int $frequency): static
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getFrequencyUnit(): ?string
    {
        return $this->frequencyUnit;
    }

    public function setFrequencyUnit(?string $frequencyUnit): static
    {
        $this->frequencyUnit = $frequencyUnit;

        return $this;
    }

    public function getNextTime(): ?\DateTime
    {
        return $this->nextTime;
    }

    public function setNextTime(?\DateTime $nextTime): static
    {
        $this->nextTime = $nextTime;

        return $this;
    }

    public function getNextTimeToStr(): ?string
    {
        return $this->nextTimeToStr;
    }

    public function setNextTimeToStr(?string $nextTimeToStr): static
    {
        $this->nextTimeToStr = $nextTimeToStr;

        return $this;
    }

    public function getNbDaysBeforeToDisplay(): ?int
    {
        return $this->nbDaysBeforeToDisplay;
    }

    public function setNbDaysBeforeToDisplay(int $nbDaysBeforeToDisplay): static
    {
        $this->nbDaysBeforeToDisplay = $nbDaysBeforeToDisplay;

        return $this;
    }

    public function getDateToDisplay(): ?\DateTime
    {
        return $this->dateToDisplay;
    }

    public function setDateToDisplay(?\DateTime $dateToDisplay): static
    {
        $this->dateToDisplay = $dateToDisplay;

        return $this;
    }

    public function isCanProcrastinate(): ?bool
    {
        return $this->canProcrastinate;
    }

    public function setCanProcrastinate(bool $canProcrastinate): static
    {
        $this->canProcrastinate = $canProcrastinate;

        return $this;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(?\DateTime $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function isLatestOperationsVisible(): ?bool
    {
        return $this->latestOperationsVisible;
    }

    public function setLatestOperationsVisible(bool $latestOperationsVisible): static
    {
        $this->latestOperationsVisible = $latestOperationsVisible;

        return $this;
    }

    /**
     * @return Collection<int, Log>
     */
    public function getLogs(): Collection
    {
        $criteria = Criteria::create()
            ->orderBy(['id' => Order::Descending]);

        return $this->logs->matching($criteria);
    }

    public function addLog(Log $log): static
    {
        if (!$this->logs->contains($log)) {
            $this->logs->add($log);
            $log->setRecurringTask($this);
        }

        return $this;
    }

    public function removeLog(Log $log): static
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getRecurringTask() === $this) {
                $log->setRecurringTask(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LinkedFile>
     */
    public function getLinkedFiles(): Collection
    {
        return $this->linkedFiles;
    }

    public function addLinkedFile(LinkedFile $linkedFile): static
    {
        if (!$this->linkedFiles->contains($linkedFile)) {
            $this->linkedFiles->add($linkedFile);
            $linkedFile->setRecurringTask($this);
        }

        return $this;
    }

    public function removeLinkedFile(LinkedFile $linkedFile): static
    {
        if ($this->linkedFiles->removeElement($linkedFile)) {
            // set the owning side to null (unless already changed)
            if ($linkedFile->getRecurringTask() === $this) {
                $linkedFile->setRecurringTask(null);
            }
        }

        return $this;
    }

    public function getProcrastinated(): ?\DateTime
    {
        return $this->procrastinated;
    }

    public function setProcrastinated(?\DateTime $procrastinated): static
    {
        $this->procrastinated = $procrastinated;

        return $this;
    }
}
