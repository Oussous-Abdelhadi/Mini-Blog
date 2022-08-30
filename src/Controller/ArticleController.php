<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article/new", name="article_new")
     * 
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
}

