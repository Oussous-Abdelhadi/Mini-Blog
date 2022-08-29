<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article/new", name="article_new")
     * 
     */
    public function new(Request $request): Response
    {
        return $this->render(view : 'article/new.html.twig');
    }
}

