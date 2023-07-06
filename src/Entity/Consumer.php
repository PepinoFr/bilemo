<?php

namespace App\Entity;

use App\Repository\ConsumerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ConsumerRepository::class)
 */
class Consumer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("consumer:all")
     * @Groups("consumer:read")
     * @Groups("consumer:delete")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|NULL
     * @Groups("consumer:all")
     * @Groups("consumer:read")
     * @Groups("consumer:add")
     * @OA\Property(type="string")
     * @Assert\NotBlank
     * @SerializedName("name")
     * @Groups({"write"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("consumer:read")
     * @var string|NULL
     * @Groups("consumer:add")
     * @OA\Property(type="string")
     * @Assert\NotBlank
     * @SerializedName("firstname")
     * @Groups({"write"})
     */
    private $firstname;
    /**
     * @Groups("consumer:all")
     * @var string|NULL
     * @OA\Property(type="string")
     * @Assert\NotBlank
     * @SerializedName("link")
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="consumers")
     * @Groups("consumer:read")
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?string
    {
        return "/api/consumer/".$this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }
}
