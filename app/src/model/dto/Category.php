<?php

namespace Moran\Model\DTO;

use \JsonSerializable;

class Category implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $description;
    private string $color;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setColor(string $color): void
    {
        $this->color = $color;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
        ];
    }
}