<?php

namespace App\Entity;

use App\Repository\ToolStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToolStatusRepository::class)
 */
class ToolStatus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tool::class, inversedBy="toolStatuses")
     */
    private $toolId;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="toolStatuses")
     */
    private $statusId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToolId(): ?Tool
    {
        return $this->toolId;
    }

    public function setToolId(?Tool $toolId): self
    {
        $this->toolId = $toolId;

        return $this;
    }

    public function getStatusId(): ?Status
    {
        return $this->statusId;
    }

    public function setStatusId(?Status $statusId): self
    {
        $this->statusId = $statusId;

        return $this;
    }
}
