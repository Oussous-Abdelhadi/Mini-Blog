<?php 

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("admin/home", name="admin_home")
     */
    public function home(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/home.html.twig', [
            'articles' => $articleRepository->findAll(),
            'categories' => $categories
        ]);
    }

    /**
     * @Route("admin/article/new", name="admin_new_article")
     */

    public function NewArticle(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();

            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('admin/home');
        }
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        return $this->render('admin/article/new.html.twig', [
            "form" => $form->createView(),
            'status' => $status_login
        ]);    }
}

