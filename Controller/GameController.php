<?php

namespace Aldaflux\GameQuizzBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bridge\Doctrine\Attribute\MapEntity;



use Aldaflux\GameQuizzBundle\Entity\Game;

use Aldaflux\GameQuizzBundle\Entity\Board;
use Aldaflux\GameQuizzBundle\Entity\Question;
use Aldaflux\GameQuizzBundle\Entity\Answer;
 

  
use Doctrine\ORM\EntityManagerInterface;
 

 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }
    
    public function GetEm()
    {
        return($this->em);
    }
     
        
    #[Route(path: '/', name: 'algq_homepage')]
    public function indexAction()
    {
        $defaultGame= $this->GetEm()->getRepository(Game::class)->findDefault();
         if ($defaultGame)
        {
            return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ['game'=>$defaultGame]);
        }
        else
        {
            return $this->render('@AldafluxGameQuizz/game/game/nogame.html.twig');
        }
    }
        
        
    #[Route(path: '/index.html', name: 'algq_homepage_2')]
    public function index2Action()
    {
        
        $defaultGame= $this->GetEm()->getRepository(Game::class)->findDefault();
 
        return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ['game'=>$defaultGame]);
    }
        
    #[Route(path: '/fini.html', name: 'algq_fini')]
    public function finiAction()
    {
        return $this->render('@AldafluxGameQuizz/game/endpage/fini.html.twig');
    }
    
    

    #[Route(path: '/game_{id}.json', methods: ['GET'], name: 'algq_game_json')]
    public function GameShowAction(Game $game)
    {
        foreach ($game->getBoards() as $board)
        {
            foreach ($board->getQuestions() as $question)
            {
                if (! $question->GetPublished())
                {
                    $board->removeQuestion($question);
                }
            }
            if (! $board->getPublished())
            {
                $game->removeBoard($board);
            }
        }
        
        
        return $this->json($game);
    }
    

    
    #[Route(path: '/game_{game}/quesion_{question}.init.js', methods: ['GET'], name: 'algq_game_init_js_question')]
    public function GameInitQuestionAction(
        #[MapEntity(mapping: ['game' => 'id'])] Game $game,
        #[MapEntity(mapping: ['question' => 'id'])] Question $question
    )
    {
        return $this->render('@AldafluxGameQuizz/game/game/init.js.twig', ["game"=>$game, "question"=>$question]);
    }

    
    #[Route(path: '/game_{game}/init.js', methods: ['GET'], name: 'algq_game_init_js')]
    public function GameInitAction(Game $game)
    {
        return $this->render('@AldafluxGameQuizz/game/game/init.js.twig', ["game"=>$game]);
    }


    #[Route(path: '/{slug}', name: 'algq_play_game')]
    public function PlayGameAction(Game $game)
    {
        return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ["game"=>$game]);
    }
        
    

    #[Route(path: '/{slug}/question_{id}', name: 'algq_play_question')]
    public function PlayQuestionAction(
        #[MapEntity(mapping: ['slug' => 'slug'])] Game $game,
        #[MapEntity(mapping: ['id' => 'id'])] Question $question
    )
    {
        
        return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ["game"=>$game,"question"=>$question]);
    }
        
    
    
    
}
