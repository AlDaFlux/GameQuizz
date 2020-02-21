<?php
 
namespace Aldaflux\GameQuizzBundle\Command;

use Symfony\Component\Console\Input\InputOption;



use Aldaflux\GameQuizzBundle\Entity\Game; 
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
 
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DependencyInjection\ContainerInterface;


 
class BuildCordovaCommand extends Command
{
    protected static $defaultName = 'game:build-cordova';
 
    private $game;
    private $gameRepo;
    private $em;
    private $container;
    private $templating;
    private $outputFolder;

    public function __construct(EntityManagerInterface $em,ContainerInterface $container)
    {
        parent::__construct();
        $this->em = $em;
        $this->container = $container;
        $this->templating = $container->get('templating');
         
        $this->gameRepo =  $this->em->getRepository(Game::class);
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
    }
    
    
   function templating($template)
   {
        $result = $this->templating->render($template, ["game"=>$this->game,"cordova"=>true]);
        $result= str_replace("src='/", "src='", $result);
        $result= str_replace('src="/', 'src="', $result);
        return($result);
        
        
       
   }
}
