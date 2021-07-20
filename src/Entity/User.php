<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client_id;

    /**
     * @ORM\OneToMany(targetEntity=Context::class, mappedBy="owner")
     */
    private $contexts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_deleted = false;

    public function __construct() {
        $this->contexts = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getClientId(): ?string {
        return $this->client_id;
    }

    public function setClientId(string $client_id): self {
        $this->client_id = $client_id;

        return $this;
    }

    /**
     * @return Collection|Context[]
     */
    public function getContexts(): Collection {
        return $this->contexts;
    }

    public function addContext(Context $context): self {
        if (!$this->contexts->contains($context)) {
            $this->contexts[] = $context;
            $context->setOwner($this);
        }

        return $this;
    }

    public function removeContext(Context $context): self {
        if ($this->contexts->removeElement($context)) {
            // set the owning side to null (unless already changed)
            if ($context->getOwner() === $this) {
                $context->setOwner(null);
            }
        }

        return $this;
    }

    public function getIsDeleted(): ?bool {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): self {
        $this->is_deleted = $is_deleted;

        return $this;
    }
}
