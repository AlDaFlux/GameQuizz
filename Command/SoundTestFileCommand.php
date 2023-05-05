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


 
class SoundTestFileCommand extends Command
{
    protected static $defaultName = 'game:sound-test';
 
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
            ->setDescription('Lists all the existing games')
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
    protected function execute(InputInterface $input, OutputInterface $output) : int
    { 
        
        $questions = $this->questionRepo->findAll([], ['id' => 'ASC']);
        foreach ($questions as $question)
        {
//            $output->writeLn($question->GetOrdre()." :  ".$question->GetId()." :  ".$question);
            
            if ($question->GetQuestionAudio())
            {
                $file=$question->GetQuestionAudio();
                if ($this->fileExist($file))
                {
                    $output->writeLn("<info>".$this->GetFolderPublic().$file."</info>");
                }
                else
                {
                    $output->writeLn("<error>".$this->GetFolderPublic().$file."</error>");
                }
            }
            
            if ($question->GetAnswerAudio())
            {
                $file=$question->GetAnswerAudio();
                if ($this->fileExist($file))
                {
                    $output->writeLn("<info>".$this->GetFolderPublic().$file."</info>");
                }
                else
                {
                    $output->writeLn("<error>".$this->GetFolderPublic().$file."</error>");
                }
            }
            if ($question->GetAnswerPlusAudio())
            {
                $file=$question->GetAnswerPlusAudio();
                if ($this->fileExist($file))
                {
                    $output->writeLn("<info>".$this->GetFolderPublic().$file."</info>");
                }
                else
                {
                    $output->writeLn("<error>".$this->GetFolderPublic().$file."</error>");
                }
            }
            
            foreach ($question->getAnswers() as $answer)
            {
                if ($answer->GetAnswerAudio())
                {
                    $file=$answer->GetAnswerAudio();
                    if ($this->fileExist($file))
                    {
                        $output->writeLn("<info>+".$this->GetFolderPublic().$file."</info>");
                    }
                    else
                    {
                        $output->writeLn("<error>".$this->GetFolderPublic().$file."</error>");
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
    
    

}
