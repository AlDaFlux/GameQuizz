<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aldaflux\GameQuizzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity()
 * @ORM\Table(name="algq_board")
 */
class Board implements \JsonSerializable
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
     * @ORM\Column(type="integer")
     */
    private $ordre;
    


    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="board")
     * @ORM\OrderBy({"ordre": "ASC"})
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="boards")
     */
    private $game;
    
    
    
    
    public function __construct()
    {
        $this->questions = new ArrayCollection();
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
            'ordre'        => $this->ordre,
            'questions'  => $this->questions,
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

   

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

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
    public function getQuestions(): Collection
    {
        return $this->questions;
    }
    
    
    public function getNbQuestions()
    {
        return count($this->questions);
    }
    
    


    public function getQuestionsPublished(): Collection
    {
        $questions = new ArrayCollection();
        foreach ($this->getQuestions() as $question)
        {
            if ($question->getPublished())
            {
                $questions->add($question);
            }
        }
        return $questions;
    }
    
    
    public function getPublished()
    {
        return($this->getNbQuestionsPublished());
    }
    
    
    public function getIsLastPublished()
    {
        return($this->getGame()->getBoardsPublishedCount()==$this->getOrdre());
    }
    
    
    
    public function getFullPublished()
    {
        return ($this->getNbQuestions()==$this->getNbQuestionsPublished());
    }
    
    
    public function getNbQuestionsPublished()
    {
        return (count($this->getQuestionsPublished()));
    }

    
    public function getQuestionsAreOrdered()
    {
        $i=0;
        foreach ($this->getQuestions() as $question)
        {
            $i++;
            if (! ($question->GetOrdre()==$i))
            {
                return(false);
            }
        }
        return(true);
    }

    
    
    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setBoard($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getBoard() === $this) {
                $question->setBoard(null);
            }
        }

        return $this;
    }
    
    public function getQuestionsCount()
    {
        return count($this->questions);
    }

    public function getQuestionsPublishedCount()
    {
        return count($this->getQuestionsPublished());
    }
    
    public function getQuestionByOrdre($ordre) 
    {
        foreach ($this->questions as $question)
        {
            if ($question->getOrdre() == $ordre)
            {
                return ($question);
            }
        }
    }
    
    public function getPublishedByOrdre($ordre) 
    {
        foreach ($this->getQuestionsPublished() as $question)
        {
            if ($question->getOrdre() == $ordre)
            {
                return ($question);
            }
        }
    }
    
    public function getPublishedQuestionFirst() 
    {
        return($this->getPublishedByOrdre(1));
    }
    
    
    

    
    public function isDeletable()
    {
        return (! ($this->getQuestionsCount()));
    }
    
    
    public function getNextBoard() 
    { 
        return ($this->getGame()->getBoardsByOrdre($this->getOrdre()+1));
    }
 
}
