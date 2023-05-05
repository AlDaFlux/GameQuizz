<?php
 
namespace Aldaflux\GameQuizzBundle\Command;


use Aldaflux\GameQuizzBundle\Entity\Game; 
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
 
use Symfony\Component\Console\Output\OutputInterface;


 
class GameListCommand extends Command
{
    protected static $defaultName = 'game:list';
 
    private $gameRepo;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->gameRepo =  $this->em->getRepository(Game::class);
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
        $games = $this->gameRepo->findBy([], ['id' => 'DESC']);
        foreach ($games as $game)
        {
            $output->writeLn($game->GetId()." :  ".$game);
        }

    }

}
