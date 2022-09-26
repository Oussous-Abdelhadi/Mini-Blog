<?php 

namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

        /**
     * @Route("admin/home", name="admin_home")
     */
    public function home(ArticleRepository $articleRepository, CommentRepository $commentRepository)
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($status_login == true) {
            
            return $this->render('admin/home.html.twig',[
                'commentaires' => $commentRepository->findAll(),
                'articles' => $articleRepository->findAll(),
            ]);
        }else {
            return $this->redirectToRoute('home');
        }
    }

    
}

