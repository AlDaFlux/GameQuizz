<?php
 
namespace Aldaflux\GameQuizzBundle\Command;


use Aldaflux\GameQuizzBundle\Entity\Game; 
use Aldaflux\GameQuizzBundle\Entity\Question; 
use Aldaflux\GameQuizzBundle\Entity\Answer; 

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
 
use Symfony\Component\Console\Output\OutputInterface;

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams; 


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

use Aldaflux\GameQuizzBundle\Service\QuizzUploader;


 
class SoundGenereCommand extends Command
{
    protected static $defaultName = 'game:sound:genere';

    private $soundDirectory;
    
    private $questionRepo;
    private $answerRepo;
    private $googleJson;
    private $extension;

    public function __construct(protected EntityManagerInterface $em,protected ParameterBagInterface $parameterBag, protected QuizzUploader $QuizzUploader)
    {
        parent::__construct();

        $this->em = $em;
        $this->extension = "wav";
        $this->parameterBag = $parameterBag;
        $this->soundDirectory=$this->parameterBag->get("aldaflux_game_quizz.folder_audio");        
        $this->publicDirectory=$this->parameterBag->get("aldaflux_game_quizz.folder_public");
        $this->questionRepo =  $this->em->getRepository(Question::class);
        $this->answerRepo =  $this->em->getRepository(Answer::class);
        $this->googleJson =  $this->parameterBag->get('aldaflux_game_quizz.google_json');
               
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Generate sounds from Google Api Text To Speech');
    }


    protected function execute(InputInterface $input, OutputInterface $output) : int
    { 
        putenv("GOOGLE_APPLICATION_CREDENTIALS=".$this->googleJson);
        
        $forceRewrite=true;
        $questions = $this->questionRepo->findAll([], ['id' => 'ASC']);
        foreach ($questions as $question)
        {
            
            $output->writeLn($question->GetOrdre()." :  ".$question->GetId()." :  ".$question);
            
            if (! $question->GetQuestionAudio() || $forceRewrite)
            {
                $board=$question->getBoard();
                
                 
                $relativePath="sons/plateau_0".$board->getOrdre()."/";
                $relativePath.="q".$question->getOrdre()."/";  
                
                $folderFull=$this->publicDirectory."/".$relativePath;
                
                if (! file_exists($folderFull)) 
                {
                    $output->writeLn("<info>mkdir ".$folderFull."</info>");
                    mkdir($folderFull, 0755,true);
                }
                if ($question->getAnswerText())
                {
                    $this->genereSound($question->getQuestionText(),$folderFull."question.".$this->extension);
                    $question->setQuestionAudio($relativePath."question.".$this->extension);
                }
                
                if ($question->getAnswerText())
                {
                    $this->genereSound($question->getAnswerText(),$folderFull."answer.".$this->extension);
                    $question->setAnswerAudio($relativePath."answer.".$this->extension);
                }
                
                $this->em->persist($question);
                
                foreach ($question->GetAnswers() as $reponse)
                {
                    if ($reponse->getAnswerText())
                    {
                        $filename="answer_".$reponse->getOrdre().".".$this->extension;
                        $this->genereSound($reponse->getAnswerText(),$folderFull.$filename);
                        $reponse->setAnswerAudio($relativePath.$filename);
                        $this->em->persist($reponse);
                    }
                }
                
                $this->em->flush();
   
            } 
        }
        return Command::SUCCESS;
    }

    function GetFolderPublic()
    {
        return($this->parameterBag->get('kernel.project_dir')."/public/");
    }
    
    function fileExist($file)
    {
        return(file_exists($this->GetFolderPublic().$file));
    }
    
    
    function genereSound($texte,$file)
    {
        $textToSpeechClient = new TextToSpeechClient();
        $input = new SynthesisInput();
        $input->setText($texte);
        $voice = new VoiceSelectionParams();
        $voice->setLanguageCode('fr-FR');
        $audioConfig = new AudioConfig();
        $audioConfig->setAudioEncoding(AudioEncoding::LINEAR16);
        $resp = $textToSpeechClient->synthesizeSpeech($input, $voice, $audioConfig);
        file_put_contents($file, $resp->getAudioContent());
                
        return(file_exists($this->GetFolderPublic().$file));
    }
    
    

}
