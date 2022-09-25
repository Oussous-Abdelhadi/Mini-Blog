<?php 

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

        /**
     * @Route("admin/home", name="admin_home")
     */
    public function home()
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($status_login == true) {

            return $this->render('admin/home.html.twig');
        }else {
            return $this->redirectToRoute('home');
        }
    }


    /**
     * @Route("admin/article/show", name="articles_show")
     */
    public function show(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($status_login == true) {
            $categories = $categoryRepository->findAll();
            return $this->render('admin/article/show.html.twig', [
                'articles' => $articleRepository->findAll(),
                'categories' => $categories
            ]);
            
        }else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("admin/article/new", name="admin_new_article")
     */

    public function NewArticle(Request $request)
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($status_login == true) {
            
            $article = new Article();
            $form = $this->createForm(ArticleType::class, $article);
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->managerRegistry->getManager();
                $em->persist($article);
                $em->flush();
                return $this->redirectToRoute('admin_home');
            }
            // dd($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'));
            return $this->render('admin/article/new.html.twig', [
                "form" => $form->createView(),
                'status' => $status_login
            ]);   
        }else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * 
     * @Route("admin/article/{id}/delete", name="article_delete")
    */

    public function delete(Article $article): RedirectResponse
    {
        $em = $this->managerRegistry->getManager();
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('admin_home');
    }

        /**
     * @Route("admin/article/{id}/edit", name="article_edit")
     * @param Article
     * @return Response
     */
    public function edit(Article $article, Request $request): Response
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($status_login == true) {
            
            $form = $this->createForm(ArticleType::class, $article);
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->managerRegistry->getManager();
                $em->flush();
                return $this->redirectToRoute('admin_home');
            }
            return $this->render('admin/article/edit.html.twig', [
                "form" => $form->createView()
            ]);
        }else {
            return $this->redirectToRoute('home');
        }
    }

    
}

