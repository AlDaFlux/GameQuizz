<?php

namespace Aldaflux\GameQuizzBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;



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
        $defaultGame= $this->GetEm()->getRepository(Game::class)->findOneById(1);
        return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ['game'=>$defaultGame]);
    }
        
        
    /**
     * @Route("/index.html", name="algq_homepage_2")
     */
    public function index2Action()
    {
        $defaultGame= $this->GetEm()->getRepository(Game::class)->findOneById(1);
        return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ['game'=>$defaultGame]);
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
    

    
    /**
     * @Route("/game_{game}/quesion_{question}.init.js", methods={"GET"}, name="algq_game_init_js_question")
     * @ParamConverter("game", options={"mapping": {"game": "id"}})
     * @ParamConverter("question", options={"mapping": {"question": "id"}})
     */
    public function GameInitQuestionAction(Game $game, Question $question)
    {
        return $this->render('@AldafluxGameQuizz/game/game/init.js.twig', ["game"=>$game, "question"=>$question]);
    }

    
    /**
     * @Route("/game_{game}/init.js", methods={"GET"}, name="algq_game_init_js")
     */
    public function GameInitAction(Game $game)
    {
        return $this->render('@AldafluxGameQuizz/game/game/init.js.twig', ["game"=>$game]);
    }


    /**
     * @Route("/{slug}", name="algq_play_game")
     */
    public function PlayGameAction(Game $game)
    {
        return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ["game"=>$game]);
    }
        
    

    /**
     * @Route("/{slug}/question_{id}", name="algq_play_question")
     * @ParamConverter("game", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("Question", options={"mapping": {"id": "id"}})
     */
    public function PlayQuestionAction(Game $game,Question $question)
    {
        return $this->render('@AldafluxGameQuizz/game/game/index.html.twig', ["game"=>$game,"question"=>$question]);
    }
        
    
    
    
}
