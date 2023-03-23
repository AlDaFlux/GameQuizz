<?php

namespace Aldaflux\GameQuizzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity()
 * @ORM\Table(name="algq_link")
 */
class Link implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
     

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;
    

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;
    
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $logo;
    

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="links")
     */
    private $game;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="links")
     */
    private $question;

    public function __construct()
    {
        $this->question = new ArrayCollection();
    }
    
    
    public function getId(): ?int
    {
        return $this->id;
    }
 
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'name'        => $this->name,
            'url'        => $this->url,
            'logo'        => $this->logo,
        ];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question[] = $question;
            $question->setLinks($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->question->contains($question)) {
            $this->question->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getLinks() === $this) {
                $question->setLinks(null);
            }
        }

        return $this;
    }
    
 
}
