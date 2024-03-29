<?php

namespace App\Entity;

use App\Repository\ToolStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToolStatusRepository::class)
 * @ORM\Table(name="tool_status",indexes={
 *     @ORM\Index(name="search_idx", columns={"created_at"})
 * })
 */
class ToolStatus
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tool::class, inversedBy="toolStatuses")
     */
    private Tool $tool;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="toolStatuses")
     */
    private Status $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $messages;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Tool
     */
    public function getTool(): Tool
    {
        return $this->tool;
    }

    /**
     * @param Tool $tool
     * @return ToolStatus
     */
    public function setTool(Tool $tool): self
    {
        $this->tool = $tool;
        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return ToolStatus
     */
    public function setStatus(Status $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMessages(): ?string
    {
        return $this->messages;
    }

    public function setMessages(?string $messages): self
    {
        $this->messages = $messages;

        return $this;
    }
}
