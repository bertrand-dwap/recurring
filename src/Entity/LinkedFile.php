<?php

namespace App\Entity;

use App\Repository\LinkedFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkedFileRepository::class)]
class LinkedFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;

    #[ORM\ManyToOne(inversedBy: 'linkedFiles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecurringTask $recurringTask = null;

    #[ORM\Column(length: 255)]
    private ?string $uniqid = null;

    public function __toString()
    {
        return $this->originalName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getRecurringTask(): ?RecurringTask
    {
        return $this->recurringTask;
    }

    public function setRecurringTask(?RecurringTask $recurringTask): static
    {
        $this->recurringTask = $recurringTask;

        return $this;
    }

    public function getUniqid(): ?string
    {
        return $this->uniqid;
    }

    public function setUniqid(string $uniqid): static
    {
        $this->uniqid = $uniqid;

        return $this;
    }
}
