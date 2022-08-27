<?php

namespace App\Entity;

use App\Repository\ToolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToolRepository::class)
 */
class Tool
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ToolStatus::class, mappedBy="toolId")
     */
    private $toolStatuses;

    public function __construct()
    {
        $this->toolStatuses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
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
            $toolStatus->setToolId($this);
        }

        return $this;
    }

    public function removeToolStatus(ToolStatus $toolStatus): self
    {
        if ($this->toolStatuses->removeElement($toolStatus)) {
            // set the owning side to null (unless already changed)
            if ($toolStatus->getToolId() === $this) {
                $toolStatus->setToolId(null);
            }
        }

        return $this;
    }
}
