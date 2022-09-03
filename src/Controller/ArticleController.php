<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ArticleController extends AbstractController
{

    /**
     * @Route("article/{id}/show", name="article_show")
     * @param Article
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig',[
            'article' => $article,
            ]);
    }
    
    /**
     * @Route("/article/new", name="article_new")
     * @param Request ,ManagerRegistry
     * @return Response
     */

    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

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
     * @param Article Request
     * @return Response
     */
    public function edit(Article $article, Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $doctrine->getManager();
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

    public function delete(Article $article, Request $request, ManagerRegistry $doctrine): RedirectResponse
    {
        $em = $doctrine->getManager();
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('home');
    }
}
