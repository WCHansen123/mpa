<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="orders")
     */
    private $User_id;

    public function __construct()
    {
        $this->User_id = new ArrayCollection();
    }
       private $list;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->User_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->User_id->contains($userId)) {
            $this->User_id[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        $this->User_id->removeElement($userId);

        return $this;
    }
}
