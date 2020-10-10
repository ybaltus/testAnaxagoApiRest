<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 * @UniqueEntity(
 *     fields={"slug"},
 *     message="This project already exists."
 * )
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(name="fully_funded", type="boolean", options={"default":false})
     */
    private $fully_funded=false;

    /**
     * @ORM\OneToMany(targetEntity=ProjectInvestment::class, mappedBy="project")
     */
    private $investments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct()
    {
        $this->investments = new ArrayCollection();
        $this->created_at = new \DateTime('now');
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Project
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Project
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get fully_funded
     *
     * @return bool|null
     */
    public function getFullyFunded(): ?bool
    {
        return $this->fully_funded;
    }

    /**
     * Set fully_funded
     *
     * @param bool $fully_funded
     * @return $this
     */
    public function setFullyFunded(bool $fully_funded): self
    {
        $this->fully_funded = $fully_funded;

        return $this;
    }

    /**
     * @return Collection|ProjectInvestment[]
     */
    public function getInvestments(): Collection
    {
        return $this->investments;
    }

    public function addInvestment(ProjectInvestment $investment): self
    {
        if (!$this->investments->contains($investment)) {
            $this->investments[] = $investment;
            $investment->setProject($this);
        }

        return $this;
    }

    public function removeInvestment(ProjectInvestment $investment): self
    {
        if ($this->investments->contains($investment)) {
            $this->investments->removeElement($investment);
            // set the owning side to null (unless already changed)
            if ($investment->getProject() === $this) {
                $investment->setProject(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}

