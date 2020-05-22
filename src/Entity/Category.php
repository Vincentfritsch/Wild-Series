<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Program", mappedBy="category")
     */
    private $programs;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->programs = new ArrayCollection();
    }

    /**
     * @return Collection|Program[]
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    /**
     * @param Program $program
     * @return Category
     */
    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program))
        {
            $this->programs[] = $program;
            $program->setCategory($this);
        }
        return $this;
    }

    /**
     * @param Program $program
     * @return Category
     */
    public function removeProgram(Program $program): self
    {
        if ($this->programs->contains($program))
        {
            $this->programs->removeElement($program);
            if ($program->getCategory() === $this)
            {
                $program->setCategory(null);
            }
        }
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
