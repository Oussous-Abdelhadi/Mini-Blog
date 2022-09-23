<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $articles = $articleRepository->findBy(["published" => true]);
        $categorys = $categoryRepository->findAll();
        // dd($articles);
        // foreach ($articles as $article) {
        //     dd($article);
        // }
        return $this->render('home/index.html.twig', [
            'articles' => $articles, 
            'categories' => $categorys
        ]);
    }
}
