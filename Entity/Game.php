<?php
 

namespace Aldaflux\GameQuizzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="Aldaflux\GameQuizzBundle\Repository\GameRepository")
 * @ORM\Table(name="algq_game")
 */
class Game implements \JsonSerializable
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
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;
    
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $soustitre;
    

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="game")
     * @ORM\OrderBy({"ordre": "ASC"})
     */
    private $questions;
    
    /**
     * @ORM\OneToMany(targetEntity="Board", mappedBy="game")
     * @ORM\OrderBy({"ordre": "ASC"})
     */
    private $boards;
    

    /**
     * @ORM\OneToMany(targetEntity="Link", mappedBy="game")
     */
    private $links;

    
    
    
    
    
    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->boards = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->links = new ArrayCollection();
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
            'soustitre'        => $this->soustitre,
            'boards'  => $this->boards,
        ];
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
 
    public function getSoustitre()
    {
        return $this->soustitre;
    }

    public function setSoustitre($soustitre)
    {
        $this->soustitre = $soustitre;
        return $this;
    }
 
    
    
    /**
     * @return Collection|Answer[]
     */
    public function getBoards(): Collection
    {
        return $this->boards;
    }
    
    
    

    public function getBoardsCount()
    {
        return count($this->boards);
    }
    
    public function getBoardsPublished()
    {
        $boards = new ArrayCollection();
        foreach ($this->getBoards() as $board)
        {
            if ($board->getPublished())
            {
                $boards->add($board);
            }
        }
        return $boards;        
    }
    
    public function getBoardsPublishedCount()
    {
        return(count($this->getBoardsPublished()));
    }
    
     public function getBoardsAreOrdered()
    {
        $i=0;
        foreach ($this->getBoards() as $board)
        {
            $i++;
            if (! ($board->GetOrdre()==$i))
            {
                return(false);
            }
        }
        return(true);
    }
    
    public function getBoardsByOrdre($ordre) 
    {
        foreach ($this->boards as $board)
        {
            if ($board->getOrdre() == $ordre)
            {
                return ($board);
            }
        }
    }
    
    
    

    
    

    public function addBoard(Board $board): self
    {
        if (!$this->boards->contains($board)) {
            $this->boards[] = $board;
            $board->setGame($this);
        }

        return $this;
    }

    public function removeBoard(Board $board): self
    {
        if ($this->boards->contains($board)) {
            $this->boards->removeElement($board);
            // set the owning side to null (unless already changed)
            if ($board->getGame() === $this) {
                $board->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }
    
    
    
    

    public function getQuestionsCount()
    {
        return count($this->questions);
    }
    
    public function isDeletable()
    {
        return (! ($this->getQuestionsCount()+$this->getBoardsCount()));
    }
    

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setGame($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getGame() === $this) {
                $question->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Link[]
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(Link $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setGame($this);
        }

        return $this;
    }

    public function removeLink(Link $link): self
    {
        if ($this->links->contains($link)) {
            $this->links->removeElement($link);
            // set the owning side to null (unless already changed)
            if ($link->getGame() === $this) {
                $link->setGame(null);
            }
        }

        return $this;
    }
    
    

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
 
}
