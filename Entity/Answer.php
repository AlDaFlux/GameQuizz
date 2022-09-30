<?php

namespace Aldaflux\GameQuizzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity()
 * @ORM\Table(name="algq_answer")
 */
class Answer implements \JsonSerializable
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
     * @ORM\Column(type="integer")
     */
    private $ordre;
    

    
    /**
     * @ORM\Column(type="text")
     */
    private $answerText;
    
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $answerAudio;
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;
   

    
    

     /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isGood;
    
    
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
            'answerText'        => $this->answerText,
            'answerAudio'        => $this->GetAnswerAudio(),
            'ordre'  => $this->ordre,
            'isGood'  => $this->isGood,
        ];
    }

    public function __toString(): string
    {
        return $this->answerText;
    }
 

    public function getAnswerText(): ?string
    {
        return $this->answerText;
    }

    public function setAnswerText(string $answerText): self
    {
        $this->answerText = $answerText;

        return $this;
    }

    public function getIsGood(): ?bool
    {
        return $this->isGood;
    }

    public function setIsGood(bool $isGood): self
    {
        $this->isGood = $isGood;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

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

    public function getAnswerAudio()
    {
        return($this->answerAudio);
    }
    

    public function setAnswerAudio(?string $answerAudio): self
    {
        $this->answerAudio = $answerAudio;

        return $this;
    }
    
    
 
}
