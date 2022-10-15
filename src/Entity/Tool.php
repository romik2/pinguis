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
     * @ORM\Column(type="string", length=30)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ToolStatus::class, mappedBy="tool")
     */
    private $toolStatuses;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tools")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=ToolType::class, inversedBy="tools")
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted = false;

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


    public function getToolStatusesLimit(int $limit): array
    {
        return array_reverse(array_slice(array_reverse($this->toolStatuses->toArray()), 0, $limit));
    }

    public function getStatus()
    {
        $status = $this->toolStatuses->toArray();
        if (end($status)) {
            return end($status)->getStatus();
        }
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getType(): ?ToolType
    {
        return $this->type;
    }

    public function setType(?ToolType $type): self
    {
        $this->type = $type;

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
}
