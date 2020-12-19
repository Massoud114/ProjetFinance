<?php

namespace App\Entity;

use App\Repository\RevenuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RevenuRepository::class)
 */
class Revenu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submit_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $denomination;

    /**
     * @ORM\Column(type="bigint")
	 * @Assert\GreaterThan(value=0, message="Entrer un montant superieur a zéro")
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="revenus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    public function __construct()
	{
		$this->submit_at = new \DateTime("now");
	}

	public function getId(): ?int
             {
                 return $this->id;
             }

    public function getSubmitAt(): ?\DateTimeInterface
    {
        return $this->submit_at;
    }

    public function setSubmitAt(\DateTimeInterface $submit_at): self
    {
        $this->submit_at = $submit_at;

        return $this;
    }

    public function getDenomination(): ?string
    {
        return $this->denomination;
    }

    public function setDenomination(string $denomination): self
    {
        $this->denomination = $denomination;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
