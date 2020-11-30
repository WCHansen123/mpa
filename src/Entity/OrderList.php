<?php

namespace App\Entity;

use App\Repository\OrderListRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderListRepository::class)
 */
class OrderList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="object")
     */
    private $list;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="OrderList", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getList()
    {
        return $this->list;
    }

    public function setList($list): self
    {
        $this->list = $list;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
