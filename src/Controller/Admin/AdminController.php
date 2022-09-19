<?php 

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("admin/home", name="admin_home")
     */
    public function home(ArticleRepository $articleRepository)
    {
        return $this->render('admin/home.html.twig', [
            'article' => $articleRepository->findAll(),
        ]);
    }
}

