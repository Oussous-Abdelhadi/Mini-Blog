<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {

        $article = new Article;
        $article->setTitre('titre1')
                ->setDescription('description')
                ->setAuteur('dads')
                ->setVideo('https://www.youtube.com/watch?v=V4NK5UXaiAA&ab_channel=ThomasMouchelet')
        ;
        return $this->render('home/index.html.twig', [
            'controller_name' => 'Dads',
            "article" => $article
        ]);
    }
}
