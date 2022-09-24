<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class ArticleController extends AbstractController
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @Route("article/{id}/show", name="article_show")
     */
    public function show(Article $article,
     CommentRepository $commentRepository,
      Request $request, $id): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $em = $this->doctrine->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirect($request->getUri());
        }
        $comments = $commentRepository->findBy(['article' =>  $article]);
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        return $this->render('article/show.html.twig',[
            'form' => $form->createView(),
            'article' => $article,
            'comments' => $comments,
            'status_login' => $status_login
        ]);
    }
    
}
