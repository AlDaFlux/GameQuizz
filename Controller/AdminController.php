<?php

namespace Aldaflux\GameQuizzBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Aldaflux\GameQuizzBundle\Service\QuizzUploader;


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



use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;




use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    
    private $quizzUploader;
    
    
    public function __construct(QuizzUploader $quizzUploader)
    {
     
        $this->quizzUploader=$quizzUploader;
        
    }


    
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
     * @Route("/board_{id}/publish_all", name="algq_admin_board_publish_all", methods={"GET"})
     */
    public function boardPublishQuestion(Board $board) 
    {
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($board->getQuestions() as $question)
        {
            $question->setPublished(true);
            $entityManager->persist($question);
        }
        $entityManager->flush();
        return $this->redirectToRoute('algq_admin_board_show', ['id'=>$board->getId()]);
    }
    
    /**
     * @Route("/board_{id}/unpublish_all", name="algq_admin_board_unpublish_all", methods={"GET"})
     */
    public function boardUnPublishQuestion(Board $board) 
    {
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($board->getQuestions() as $question)
        {
            $question->setPublished(false);
            $entityManager->persist($question);
        }
        $entityManager->flush();
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
        
        $form = $this->createForm(QuestionType::class, $question, ['fields'=>$this->getParameter("aldaflux_game_quizz.fields")]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            

            $folder="plateau_0".$board->getOrdre()."/";
            $folder.="q".$form->get('ordre')->getData()."/";  

            $file = $form->get('questionAudioFichier')->getData();
            if ($file)
            {
                $question->setQuestionAudio($this->quizzUploader->uploadSound($file, $folder, "question"));
            }
            $file = $form->get('answerAudioFichier')->getData();
            if ($file)
            {
                $question->setAnswerAudio($this->quizzUploader->uploadSound($file, $folder, "answer"));
            }
            
            $file = $form->get('answerPlusAudioFichier')->getData();
            if ($file)
            {
                $question->setAnswerPlusAudio($this->quizzUploader->uploadSound($file, $folder, "answer_plus"));
            }
            $file = $form->get('questionAudioFichier')->getData();
            if ($file)
            {
                $question->setQuestionAudio($this->quizzUploader->uploadSound($file, $folder, "question"));
            }
            $file = $form->get('answerAudioFichier')->getData();
            if ($file)
            {
                $question->setAnswerAudio($this->quizzUploader->uploadSound($file, $folder, "answer"));
            }
            
            $file = $form->get('answerPlusAudioFichier')->getData();
            if ($file)
            {
                $question->setAnswerPlusAudio($this->quizzUploader->uploadSound($file, $folder, "answer_plus"));
            }
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_question_show', ['id'=>$question->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Question/new.html.twig', [
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
            return $this->render('@AldafluxGameQuizz/admin/Question/show.html.twig', ['question'=>$question, "fields"=>$this->getParameter("aldaflux_game_quizz.fields")]);
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
        
             //, QuizzUploader $fileUploader     
        $form = $this->createForm(QuestionType::class, $question, ['fields'=>$this->getParameter("aldaflux_game_quizz.fields")]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
             
            
            $ordre=$form->get('ordre')->getData();
            if ($ordre<10)
            {
                $ordre0='0'.$ordre;
            }
            else
            {
                $ordre0=$ordre;
            }
//            $ordre0=printf('%.2f',$form->get('ordre')->getData());
            

                        
            $folder=$this->getQuestionFolder($question);
            
            $file = $form->get('questionAudioFichier')->getData();
            if ($file)
            {
                $question->setQuestionAudio($this->quizzUploader->uploadSound($file, $folder, "question"));
            }
            $file = $form->get('answerAudioFichier')->getData();
            if ($file)
            {
                $question->setAnswerAudio($this->quizzUploader->uploadSound($file, $folder, "answer"));
            }
            
            $file = $form->get('answerPlusAudioFichier')->getData();
            if ($file)
            {
                $question->setAnswerPlusAudio($this->quizzUploader->uploadSound($file, $folder, "answer_plus"));
            }
            
            
            $folder=$this->getQuestionFolderVideo($question);
            
            $file = $form->get('questionVideoFichier')->getData();
            if ($file)
            {
                $question->setQuestionVideo($this->quizzUploader->uploadVideo($file, $folder, "q".$ordre0));
            }
            $file = $form->get('answerVideoFichier')->getData();
            if ($file)
            {
                $question->setAnswerVideo($this->quizzUploader->uploadVideo($file, $folder, "q".$ordre0."-end"));
            }

            
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('algq_admin_question_show', ['id'=>$question->getId()]);
        }
        return $this->render('@AldafluxGameQuizz/admin/Question/edit.html.twig', [
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
    
    
    public function getQuestionFolder(Question $question)
    {
        $folder="plateau_0".$question->getBoard()->getOrdre()."/";
        $folder.="q".$question->getOrdre()."/";  
        return($folder);
        
    }
    
    public function getQuestionFolderVideo(Question $question)
    {
        $ordre0=str_pad($question->getOrdre(), 2, '0', STR_PAD_LEFT);
        $folder="p".$question->getBoard()->getOrdre()."_q".$ordre0."/";
        return($folder);
        
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
            
            $folder=$this->getQuestionFolder($question);
            $ordre=$form->get('ordre')->getData();
            $file = $form->get('answerAudioFichier')->getData();
            if ($file)
            {
                $questionAudioFileName = $this->quizzUploader->uploadSound($file, $folder, "answer_".$ordre);
                $answer->setAnswerAudio($questionAudioFileName);
            }
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
        $question=$answer->GetQuestion();
        
        $form = $this->createForm(AnswerType::class, $answer );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            
            
            $folder=$this->getQuestionFolder($question);
            $ordre=$form->get('ordre')->getData();
            $file = $form->get('answerAudioFichier')->getData();
            if ($file)
            {
                $questionAudioFileName = $this->quizzUploader->uploadSound($file, $folder, "answer_".$ordre);
                $answer->setAnswerAudio($questionAudioFileName);
            }

            
            
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
