<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        return $this->render('article/show.html.twig',[
            'form' => $form->createView(),
            'article' => $article,
            'comments' => $comments
        ]);
    }
    
    /**
     * @Route("/article/new", name="article_new")
     * @param Request ,ManagerRegistry
     * @return Response
     */

    public function new(): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();

            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('article/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("article/{id}/edit", name="article_edit")
     * @param Article
     * @return Response
     */
    public function edit(Article $article, Request $request): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->doctrinene->getManager();
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('article/edit.html.twig', [
            "form" => $form->createView()
        ]);
    }

        /**
     * 
     * @Route("article/{id}/delete", name="article_delete")
     */

    public function delete(Article $article): RedirectResponse
    {
        $em = $this->doctrine->getManager();
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('home');
    }
}
