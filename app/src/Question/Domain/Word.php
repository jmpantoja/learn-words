<?php

namespace LearnWords\Question\Domain;

class Word
{
    private $id;

    private $name;

    private $active;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }
}
