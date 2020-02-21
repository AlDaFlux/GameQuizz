<?php

namespace Aldaflux\GameQuizzBundle\Controller;

use Symfony\Component\HttpFoundation\Request;


use Aldaflux\GameQuizzBundle\Entity\Game;
use Aldaflux\GameQuizzBundle\Entity\Board;
use Aldaflux\GameQuizzBundle\Entity\Question;
use Aldaflux\GameQuizzBundle\Entity\Answer;
use Aldaflux\GameQuizzBundle\Entity\Link;

use Aldaflux\GameQuizzBundle\Form\GameType;
use Aldaflux\GameQuizzBundle\Form\BoardType;
use Aldaflux\GameQuizzBundle\Form\QuestionType;
use Aldaflux\GameQuizzBundle\Form\AnswerType;
use Aldaflux\GameQuizzBundle\Form\LinkType;






use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    
    public function GetEm()
    {
 
        $em = $this->getDoctrine()->getManager();
        return($em);
    }
        
        
    /**
     * @Route("/", name="algq_admin_homepage")
     */
    public function indexAction()
    {
        return $this->render('@AldafluxGameQuizz/admin/Game/index.html.twig', ['games'=>$this->GetEm()->getRepository(Game::class)->findAll()]);
    }

    /**
     * @Route("/game_{id}", methods={"GET"}, name="algq_admin_game_show")
     */
    public function GameShowAction(Game $game)
    {
        return $this->render('@AldafluxGameQuizz/admin/Game/show.html.twig', ['game'=>$game]);
    }
    
    /**
     * @Route("/game_{id}/edit", methods={"GET","POST"}, name="algq_admin_game_edit")
     */
    public function GameEditAction(Request $request, Game $game)
    {
        $form = $this->createForm(GameType::class, $game );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_game_show', ['id'=>$game->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Game/newedit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
        
    }
    

    /**
     * @Route("/game/new", name="algq_admin_game_new", methods={"GET","POST"})
     */
    public function newGame(Request $request) 
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_game_show', ['id'=>$game->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Game/newedit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    
    
    /**
     * @Route("/game_{id}/delete", methods={"DELETE"}, name="algq_admin_game_delete")
     */
    public function GameDeleteAction(Request $request,Game $game)
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($game);
            $entityManager->flush();
            $this->addFlash('success', 'DELETE : '.$game);
        }
        return $this->redirectToRoute('algq_admin_homepage');
    }
    
    
    
    
    
    /**
     * @Route("/game_{id}/new_link", name="algq_admin_game_new_link", methods={"GET","POST"})
     */
    public function GameNewLink(Game $game, Request $request) 
    {
        $link = new Link();
        $link->setGame($game);
        
        $form = $this->createForm(LinkType::class, $link );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($link);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_link_show', ['id'=>$link->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Link/newedit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    
    
    /**
     * @Route("/link_{id}", name="algq_admin_link_show", methods={"GET","POST"})
     */
    public function GameLinkShow(Link $link) 
    {  
        return $this->render('@AldafluxGameQuizz/admin/Link/show.html.twig', [
            'game' => $link->getGame(),
            'link' => $link 
        ]);
    }

    
    /**
     * @Route("/game_{id}/new_board", name="algq_admin_game_new_board", methods={"GET","POST"})
     */
    public function GameNewBoard(Game $game, Request $request) 
    {
        $board = new Board();
        $board->setGame($game);
        $board->setOrdre($game->getBoardsCount()+1);
        
        $form = $this->createForm(BoardType::class, $board );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($board);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_board_show', ['id'=>$board->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Board/newedit.html.twig', [
            'game' => $game,
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/board_{id}/edit", methods={"GET","POST"}, name="algq_admin_board_edit")
     */
    public function BoardEditAction(Request $request, Board $board)
    {
        $form = $this->createForm(BoardType::class, $board );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($board);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_board_show', ['id'=>$board->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Board/newedit.html.twig', [
            'game' => $board->getGame(),
            'board' => $board,
            'form' => $form->createView(),
        ]);
        
    }
    
    
    
    /**
     * @Route("/board_{id}/delete", methods={"DELETE"}, name="algq_admin_board_delete")
     */
    public function BoardDeleteAction(Request $request, Board $board)
    {
        
        $id_game=$board->getGame()->getId();
         if ($this->isCsrfTokenValid('delete'.$board->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($board);
            $entityManager->flush();
            $this->addFlash('success', 'DELETE : '.$board);
        }
        return $this->redirectToRoute('algq_admin_game_show', ['id'=>$id_game]);        
    }
    
    
    /**
     * @Route("/board_{id}", methods={"GET"}, name="algq_admin_board_show")
     */
    public function BoardShowAction(Board $board)
    {
        
        return $this->render('@AldafluxGameQuizz/admin/Board/show.html.twig', ['board'=>$board]);
    }
    
    /**
     * @Route("/board_{id}/reorder", methods={"GET"}, name="algq_admin_board_reorder")
     */
    public function BoardReorderAction(Board $board)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $i=0;
        foreach ($board->getQuestions() as $question)
        {
            $i++;
            $question->SetOrdre($i);
            $entityManager->persist($question);
        }
        $entityManager->flush();
        $this->addFlash('success', 'Les questions ont été réordonnées');
        return $this->redirectToRoute('algq_admin_board_show', ['id'=>$board->getId()]);
    }
    
    
    
    
    /**
     * @Route("/board_{id}/new_question", name="algq_admin_board_new_question", methods={"GET","POST"})
     */
    public function BoardNewQuestion(Board $board, Request $request) 
    {
        $question = new Question();
        $question->setBoard($board);
        $question->setGame($board->getGame());
        $question->setOrdre($board->getQuestionsCount()+1);
        
        $form = $this->createForm(QuestionType::class, $question );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_question_show', ['id'=>$question->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Question/newedit.html.twig', [
            'game' => $board->getGame(),
            'board' => $board,
            'form' => $form->createView(),
        ]);
    }

    
    
    /**
     * @Route("/question_{id}", methods={"GET"}, name="algq_admin_question_show")
     */
    public function QuestionShowAction(Question $question)
    {
        return $this->render('@AldafluxGameQuizz/admin/Question/show.html.twig', ['question'=>$question]);
    }
     

    /**
     * @Route("/question_{id}/reorder", methods={"GET"}, name="algq_admin_question_reorder")
     */
    public function QuestionReorderAction(Question $question)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $i=0;
        foreach ($question->getAnswers() as $answer)
        {
            $i++;
            $answer->SetOrdre($i);
            $entityManager->persist($answer);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Les réponses ont été réordonnées');
        return $this->redirectToRoute('algq_admin_question_show', ['id'=>$question->getId()]);
    }
    
    
    /**
     * @Route("/reorderall", methods={"GET"}, name="algq_admin_")
   
    public function reodredAll()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        foreach ($this->GetEm()->getRepository(Question::class)->findAll() as $question)
        {
            $iiis=range(1, $question->getAnswersCount());
            shuffle($iiis); 
            foreach ($question->getAnswers() as $answer)
            {
                $answer->setOrdre(array_pop($iiis));
                $entityManager->persist($answer);
            }
            $entityManager->flush();
        }
        $this->addFlash('success', 'Les réponses ont été mélangées');
        return $this->redirectToRoute('algq_admin_homepage');
    }*/
    
    /**
     * @Route("/question_{id}/shuffle", methods={"GET"}, name="algq_admin_question_shuffle")
     */
    public function QuestionShuffleAction(Question $question)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $iiis=range(1, $question->getAnswersCount());
        shuffle($iiis); 

        foreach ($question->getAnswers() as $answer)
        {
            $answer->setOrdre(array_pop($iiis));
            $entityManager->persist($answer);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Les réponses ont été mélangées');
        return $this->redirectToRoute('algq_admin_question_show', ['id'=>$question->getId()]);
    }
    
    
    
    
    /**
     * @Route("/question_{id}/edit", methods={"GET","POST"}, name="algq_admin_question_edit")
     */
    public function QuestionEditAction(Request $request, Question $question)
    {
        $form = $this->createForm(QuestionType::class, $question );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_question_show', ['id'=>$question->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Question/newedit.html.twig', [
            'game' => $question->getGame(),
            'board' => $question->getBoard(),
            'question' => $question,
            'form' => $form->createView(),
        ]);
        
    }
    
    
    /**
     * @Route("/question_{id}/delete", methods={"DELETE"}, name="algq_admin_question_delete")
     */
    public function QuestionDeleteAction(Request $request, Question $question)
    {
        
        $id_game=$question->getGame()->getId();
         if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
            $this->addFlash('success', 'DELETE : '.$question);
        }
        return $this->redirectToRoute('algq_admin_game_show', ['id'=>$id_game]);        
    }
    
    
    
    /**
     * @Route("/question_{id}/new_answer", name="algq_admin_question_new_answer", methods={"GET","POST"})
     */
    public function QuestionNewAnswer(Question $question, Request $request) 
    {
        $answer = new Answer();
        $answer->setQuestion($question);
        $answer->setOrdre($question->getAnswersCount()+1);
        
        $form = $this->createForm(AnswerType::class, $answer );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_question_show', ['id'=>$question->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Answer/newedit.html.twig', [
            'game' => $question->getGame(),
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/answer_{id}", methods={"GET"}, name="algq_admin_answer_show")
     */
    public function AnswerShowAction(Answer $answer)
    {
        return $this->render('@AldafluxGameQuizz/admin/Answer/show.html.twig', ['answer'=>$answer, 'question'=>$answer->getQuestion()]);
    }
             
      
    /**
     * @Route("/answer_{id}/edit", methods={"GET","POST"}, name="algq_admin_answer_edit")
     */
    public function AnswerEditAction(Request $request, Answer $answer)
    {
        $form = $this->createForm(AnswerType::class, $answer );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($answer);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_question_show', ['id'=>$answer->getQuestion()->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Answer/newedit.html.twig', [
            'game' => $answer->getQuestion()->getGame(),
            'board' => $answer->getQuestion()->getBoard(),
            'question' => $answer->getQuestion(),
            'answer' => $answer,
            'form' => $form->createView(),
        ]);
        
    }
    
    
    
    /**
     * @Route("/answer_{id}/delete", methods={"DELETE"}, name="algq_admin_answer_delete")
     */
    public function AnswerDeleteAction(Request $request, Answer $answer)
    {
        
        $id_question=$answer->getQuestion()->getId();
         if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($answer);
            $entityManager->flush();
            $this->addFlash('success', 'DELETE : '.$answer);
        }
        return $this->redirectToRoute('algq_admin_question_show', ['id'=>$id_question]);        
    }
    
    
    
    
    
    
}
