<?php
 
namespace Aldaflux\GameQuizzBundle\Command;
use Symfony\Component\Console\Input\InputOption;

use Aldaflux\GameQuizzBundle\Entity\Game; 
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
 
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;


use Symfony\Component\Serializer\SerializerInterface;

 
class BuildCordovaCommand extends Command
{
    protected static $defaultName = 'game:build-cordova';
 
    private $game;
    private $gameRepo;
    private $em;
    private $container;
    private $templating;
    private $outputFolder;
    private $serializer;

    public function __construct(EntityManagerInterface $em,ContainerInterface $container, SerializerInterface $serializer)
    {
        parent::__construct();
        $this->em = $em;
        $this->container = $container;
        $this->templating = $container->get('templating');
         
        $this->gameRepo =  $this->em->getRepository(Game::class);
        
        /*
        $encoders = [new  JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
        */
        
        $this->serializer = $serializer;

    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Build cordova www')
            ->addOption('game_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du jeu ",0)
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

        $filesystem = new Filesystem();
        
        
        $game_id = $input->getOption('game_id');
        if (!$game_id)
        {
            $output->writeLn(" --game_id=? ");
            echo $this->GetHelp();
            return(0);
        }
        $this->game = $this->gameRepo->findOneById($game_id);

        $output->writeLn($this->game->GetId()." :  ".$this->game);

        $initjs = $this->templating('@AldafluxGameQuizz/game/game/init.js.twig');
        $index = $this->templating('@AldafluxGameQuizz/game/game/index_blank.html.twig');
        $fini = $this->templating('@AldafluxGameQuizz/game/endpage/fini_blank.html.twig');
        
        
        $filesystem->mirror("vendor/aldaflux/game-quizz-bundle/Resources/public/css/","build_cordova/css");
        $filesystem->mirror("vendor/aldaflux/game-quizz-bundle/Resources/public/img/","build_cordova/img");
        $filesystem->mirror("vendor/aldaflux/game-quizz-bundle/Resources/public/js/","build_cordova/js");
        $filesystem->mirror("vendor/aldaflux/game-quizz-bundle/Resources/public/sons/","build_cordova/sons");
        
//        $filesystem->mirror("public/game_quizz_data","build_cordova/game_quizz_data");
        
        $filesystem->mirror("assets/img/icons","build_cordova/img/icons");
        $filesystem->mirror("assets/img/partenaires","build_cordova/img/partenaires");
        
        $filesystem->mirror("public/sons/","build_cordova/sons");
        
        

        
        
        if (file_exists("build_cordova"))
        {
           $output->writeLn("build_cordova OK");
        }
        else
        {
           $output->writeLn("<error>build_cordova PAS OK</error>");
        }
        
        $fp = fopen('build_cordova/js/init.js', 'w');
        fwrite($fp, $initjs);
        fclose($fp);
        $fp = fopen('build_cordova/index.html', 'w');
        fwrite($fp, $index);
        fclose($fp);
        $fp = fopen('build_cordova/fini.html', 'w');
        fwrite($fp, $fini);
        fclose($fp);
        
        
        
        
        foreach ($this->game->getBoards() as $board)
        {
            $output->writeLn($board);
            foreach ($board->getQuestions() as $question)
            {
                $output->writeLn("----".$question);
                if (! $question->GetPublished())
                {
                    $board->removeQuestion($question);
                }
            }
            if (! $board->getPublished())
            {
                $this->game->removeBoard($board);
            }
        }
        $filesystem->dumpFile('build_cordova/game_quizz_data/game.json', $this->serializer->serialize($this->game, 'json'));
        
        
    }
    
    
   function templating($template)
   {
        $result = $this->templating->render($template, ["game"=>$this->game,"cordova"=>true]);
//        $result= str_replace("src='/build/", "src='", $result);
        $result= str_replace('src="/', 'src="', $result);
        $result= str_replace("src='/", "src='", $result);
        $result= str_replace("build/", "", $result);
        return($result);
        
        
       
   }
}
