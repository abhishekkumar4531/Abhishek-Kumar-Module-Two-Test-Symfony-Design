<?php

namespace App\Entity;

use App\Repository\AddedItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddedItemRepository::class)]
class AddedItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $itemValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItemValue(): ?string
    {
        return $this->itemValue;
    }

    public function setItemValue(string $itemValue): self
    {
        $this->itemValue = $itemValue;

        return $this;
    }
}
