<?php

namespace Aldaflux\GameQuizzBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
 
use Aldaflux\GameQuizzBundle\Entity\Game;
use Aldaflux\GameQuizzBundle\Entity\Board;
use Aldaflux\GameQuizzBundle\Entity\Question;
use Aldaflux\GameQuizzBundle\Entity\Answer;
 




use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GameController extends Controller
{
    
    public function GetEm()
    {
 
        $em = $this->getDoctrine()->getManager();
        return($em);
    }
        
        
    /**
     * @Route("/", name="algq_homepage")
     */
    public function indexAction()
    {
        return $this->render('@AldafluxGameQuizz/game/index.html.twig');
    }
        
        
    /**
     * @Route("/index.html", name="algq_homepage_2")
     */
    public function index2Action()
    {
        return $this->render('@AldafluxGameQuizz/game/index.html.twig');
    }
        
    /**
     * @Route("/fini.html", name="algq_fini")
     */
    public function finiAction()
    {
        return $this->render('@AldafluxGameQuizz/game/endpage/fini.html.twig');
    }
    
    

    /**
     * @Route("/game_{id}.json", methods={"GET"}, name="algq_game_json")
     */
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
        }
        return $this->json($game);
    }
    
    
    
}
