<?php
 
namespace Aldaflux\GameQuizzBundle\Command;
use Aldaflux\GameQuizzBundle\Entity\Game; 
use Aldaflux\GameQuizzBundle\Entity\Question; 
use Aldaflux\GameQuizzBundle\Entity\Answer; 

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
 
use Symfony\Component\Console\Output\OutputInterface;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


 
class WavToMp3Command extends Command
{
    protected static $defaultName = 'game:sound-tomp3';
 
    protected $parameterBag;
    
    private $questionRepo;
    private $answerRepo;
    private $em;

    public function __construct(EntityManagerInterface $em,ParameterBagInterface $parameterBag)
    {
        parent::__construct();
        $this->em = $em;
        $this->parameterBag = $parameterBag;
        $this->questionRepo =  $this->em->getRepository(Question::class);
        $this->answerRepo =  $this->em->getRepository(Answer::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Transform wav to mp3 id bd')
            ->setHelp(<<<'HELP'
 
  <info>php %command.full_name%</info> 

HELP
            )
           
           
        ;
    }

    /**
     * This method is executed after initialize(). It usually contains the logic
     * to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    { 
        
        $questions = $this->questionRepo->findAll([], ['id' => 'ASC']);
        foreach ($questions as $question)
        {
//            $output->writeLn($question->GetOrdre()." :  ".$question->GetId()." :  ".$question);
            
            if ($question->GetQuestionAudio())
            {
                $file=$question->GetQuestionAudio();
                if ($this->isWav($file) && $this->mp3Exist($file))
                {
                    $output->writeLn("<info>".$file." -->".$this->getMp3FromWav($file)."</info>");
                    $question->SetQuestionAudio($this->getMp3FromWav($file));
                    $this->em->persist($question);
                    $this->em->flush();
                }
            }
            if ($question->GetAnswerAudio())
            {
                $file=$question->GetAnswerAudio();
                if ($this->isWav($file) && $this->mp3Exist($file))
                {
                    $output->writeLn("<info>".$file." -->".$this->getMp3FromWav($file)."</info>");
                    $question->SetAnswerAudio($this->getMp3FromWav($file));
                    $this->em->persist($question);
                    $this->em->flush();
                }
            }
            if ($question->GetAnswerPlusAudio())
            {
                $file=$question->GetAnswerPlusAudio();
                if ($this->isWav($file) && $this->mp3Exist($file))
                {
                    $output->writeLn("<info>".$file." -->".$this->getMp3FromWav($file)."</info>");
                    $question->SetAnswerPlusAudio($this->getMp3FromWav($file));
                    $this->em->persist($question);
                    $this->em->flush();
                }
            }
            
            foreach ($question->getAnswers() as $answer)
            {
                if ($answer->GetAnswerAudio())
                {
                    $file=$answer->GetAnswerAudio();
                    if ($this->isWav($file) && $this->mp3Exist($file))
                    {
                        $output->writeLn("<info>".$file." -->".$this->getMp3FromWav($file)."</info>");
                        $answer->SetAnswerAudio($this->getMp3FromWav($file));
                        $this->em->persist($answer);
                        $this->em->flush();
                    }
                } 
            }
        }
    }

    function GetFolderPublic()
    {
        return($this->parameterBag->get('kernel.project_dir')."/public/");
    }
    
    function fileExist($file)
    {
        return(file_exists($this->GetFolderPublic().$file));
    }
    
    function mp3Exist($file)
    {
        return(file_exists( $this->GetFolderPublic().$this->getMp3FromWav($file)));
    }
    
    function getMp3FromWav($file)
    {
        return(str_replace(".wav", ".mp3", $file));
    }
    
    function isWav($file)
    {
        return(substr($file, -4)==".wav");
    }
    

}
