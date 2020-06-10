<?php

namespace LearnWords\ModuleA\Domain;

class Dummy
{
    private $name;

    private $id;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
