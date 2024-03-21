<?php
 
namespace Aldaflux\GameQuizzBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Aldaflux\GameQuizzBundle\Repository\QuestionRepository")
 * @ORM\Table(name="algq_question")
 */
class Question implements \JsonSerializable
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
     * @ORM\ManyToOne(targetEntity="Link", inversedBy="question")
     */
    private $links;
    

    
     
    
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return [
            'id'           => $this->id,
            'name'        => $this->name,
            'ordre'  => $this->ordre,
            'answers'  => $this->answers,
            'questionText'        => $this->questionText,
            'questionAudio'        => $this->questionAudio,
            'questionVideo'  => $this->questionVideo,
            'questionVideoLink'  => $this->questionVideoLink,
            'questionVideoYoutube'  => $this->questionVideoYoutube,
            'questionHasVideo'  => $this->getQuestionHasVideo(),
            'answerHasVideo'  => $this->getAnswerHasVideo(),
            'answerText'        => $this->answerText,
            'answerAudio'  => $this->answerAudio,
            'answerPlusText'        => $this->answerPlusText,
            'answerPlusAudio'  => $this->answerPlusAudio,
            'answerVideo'  => $this->answerVideo,
            'answerVideoYoutube'  => $this->answerVideoYoutube,
            'nextQuestionId'  => $this->getNextQuestionPublishedId(),
            'nextQuestionBoardId'  => $this->getNextQuestionBoadId(),
            'lastBoardQuestionButNotLast'  => ($this->getNextQuestionBoadId() && ($this->getNextQuestionBoadId() != $this->getBoard()->GetId())),
            
        ];
    }

    
    
    

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="questions")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity="Board", inversedBy="questions")
     */
    private $board;
    
 
    
    

    /**
     * @ORM\Column(type="integer")
     */
    private $ordre;
    

    
    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     */
    #[Gedmo\Slug(fields: ['name'])]
    private $slug;
    

    
    /**
     * @ORM\Column(type="text")
     */
    private $questionText;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $answerText;


    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $questionVideo;
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $questionVideoLink;
    
    
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $questionAudio;
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerAudio;
    
    
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerVideo;
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerVideoLink;
    
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $questionVideoYoutube;
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerVideoYoutube;
    
    
    
        /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $answerPlusText;

    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $answerPlusAudio;
    
    
    
    
    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     * @ORM\OrderBy({"ordre": "ASC"})
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    
    

     /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $published;
    
    
   
    public function __toString(): string
    {
        return $this->name;
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

    public function getQuestionText(): ?string
    {
        return $this->questionText;
    }

    public function setQuestionText(string $questionText): self
    {
        $this->questionText = $questionText;

        return $this;
    }

    public function getAnswerText()
    {
        return $this->answerText;
    }
    
    public function setAnswerText($answerText): self
    {
        $this->answerText = $answerText;
        return $this;
    }

    public function getQuestionVideo(): ?string
    {
        return $this->questionVideo;
    }

    public function setQuestionVideo(string $questionVideo): self
    {
        $this->questionVideo = $questionVideo;
        return $this;
    }
    
    public function getQuestionVideoLink(): ?string
    {
        return $this->questionVideoLink;
    }

    public function setQuestionVideoLink(string $questionVideoLink): self
    {
        $this->questionVideoLink = $questionVideoLink;
        return $this;
    }

    public function getAnswerVideo() 
    {
        return $this->answerVideo;
    }

    public function setAnswerVideo($answerVideo): self
    {
        $this->answerVideo = $answerVideo;
        return $this;
    }
    
    public function getAnswerVideoLink() 
    {
        return $this->answerVideoLink;
    }

    public function setAnswerVideoLink($answerVideoLink): self
    {
        $this->answerVideoLink = $answerVideoLink;
        return $this;
    }
    

    public function getQuestionHasVideo()
    {
        return ($this->questionVideoYoutube or $this->questionVideo or $this->questionVideoLink);
    }
    public function getAnswerHasVideo(): ?string
    {
        return ($this->answerVideo or $this->answerVideoLink or $this->answerVideoYoutube);
    }
    
    public function getQuestionVideoYoutube()
    {
        return $this->questionVideoYoutube;
    }

    public function setQuestionVideoYoutube($questionVideoYoutube): self
    {
        $this->questionVideoYoutube = $questionVideoYoutube;

        return $this;
    }

    public function getAnswerVideoYoutube() 
    {
        return $this->answerVideoYoutube;
    }

    public function setAnswerVideoYoutube($answerVideoYoutube): self
    {
        $this->answerVideoYoutube = $answerVideoYoutube;

        return $this;
    }


    
    
    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    
    
    public function getAnswersCount(): int
    {
        return count($this->answers);
    }


    
    public function getAnswersAreOrdered()
    {
        $i=0;
        foreach ($this->getAnswers() as $answer)
        {
            $i++;
            if (! ($answer->GetOrdre()==$i))
            {
                return(false);
            }
        }
        return(true);
    }

    

    public function isDeletable()
    {
        return (! ($this->getAnswersCount()));
    }
        
    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

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

    public function getQuestionAudio(): ?string
    {
        return $this->questionAudio;
    }

    public function setQuestionAudio(?string $questionAudio): self
    {
        $this->questionAudio = $questionAudio;

        return $this;
    }

    public function getAnswerAudio()
    {
        return $this->answerAudio;
    }

    public function setAnswerAudio(?string $answerAudio): self
    {
        $this->answerAudio = $answerAudio;
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

    public function getBoard(): ?Board
    {
        return $this->board;
    }

    public function setBoard(?Board $board): self
    {
        $this->board = $board;

        return $this;
    }
    
    

    public function getPublished(): ?bool
    {
        return $this->published;
    }
    
    public function setPublished(bool $published): self
    {
        $this->published = $published;
        return $this;
    }    

    function getNextQuestionPublishedId()
    {
        $q=$this->getNextQuestionPublished();
        if ($q) return($this->getNextQuestionPublished()->GetId());
    }
    
    
    function getNextQuestionBoadId()
    {
        $nextQuestion=$this->getNextQuestionPublished();
        if ($nextQuestion)
        {
            return($nextQuestion->getBoard()->GetId());
        }
    }
    
    
    function getNextQuestionPublished()
    {
        if ($this->getOrdre() < $this->getBoard()->getQuestionsPublishedCount())
        {
            return($this->getBoard()->getQuestionByOrdre($this->getOrdre()+1));
        }
        elseif (! $this->getBoard()->getIsLastPublished())
        {
            return($this->getBoard()->getNextBoard()->getPublishedQuestionFirst());
        }
    }
    
    

    public function getLinks(): ?Link
    {
        return $this->links;
    }

    public function setLinks(?Link $links): self
    {
        $this->links = $links;

        return $this;
    }

    public function getAnswerPlusText(): ?string
    {
        return $this->answerPlusText;
    }

    public function setAnswerPlusText(?string $answerPlusText): self
    {
        $this->answerPlusText = $answerPlusText;

        return $this;
    }

    public function getAnswerPlusAudio(): ?string
    {
        return $this->answerPlusAudio;
    }

    public function setAnswerPlusAudio(?string $answerPlusAudio): self
    {
        $this->answerPlusAudio = $answerPlusAudio;

        return $this;
    }
    
 
}
