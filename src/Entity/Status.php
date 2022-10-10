<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $service;

    /**
     * @ORM\OneToMany(targetEntity=ToolStatus::class, mappedBy="status")
     */
    private $toolStatuses;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    public function __construct()
    {
        $this->toolStatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function isService(): ?boolean
    {
        return $this->service;
    }

    public function setService(boolean $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection<int, ToolStatus>
     */
    public function getToolStatuses(): Collection
    {
        return $this->toolStatuses;
    }

    public function addToolStatus(ToolStatus $toolStatus): self
    {
        if (!$this->toolStatuses->contains($toolStatus)) {
            $this->toolStatuses[] = $toolStatus;
            $toolStatus->setStatusId($this);
        }

        return $this;
    }

    public function removeToolStatus(ToolStatus $toolStatus): self
    {
        if ($this->toolStatuses->removeElement($toolStatus)) {
            // set the owning side to null (unless already changed)
            if ($toolStatus->getStatusId() === $this) {
                $toolStatus->setStatusId(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
