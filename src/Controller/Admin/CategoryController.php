<?php 

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("admin/category", name="category")
     */
    public function showAll(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return$this->render('admin/category/show.html.twig', [
            'categories' => $categories,
        ]);
    }

        /**
     * @Route("admin/category/new", name="category_new")
     */

    public function new(Request $request)
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($status_login == true) {
            
            $category = new Category();
            $form = $this->createForm(CategoryType::class, $category);
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $name = strtolower(str_replace(' ', '-', strtolower($category->getName())));
                $category->setSlug($name);
                $em = $this->managerRegistry->getManager();
                $em->persist($category);
                $em->flush();
                return $this->redirectToRoute('category');
            }
            // dd($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'));
            return $this->render('admin/category/new.html.twig', [
                "form" => $form->createView(),
            ]);   
        }else {
            return $this->redirectToRoute('home');
        }
    }

    /**
     * 
     * @Route("admin/category/{id}/delete", name="category_delete")
    */

    public function delete(Category $category): RedirectResponse
    {
        $em = $this->managerRegistry->getManager();
        $em->remove($category);
        $em->flush();
        return $this->redirectToRoute('category');
    }

    /**
     * @Route("admin/category/{id}/edit", name="category_edit")
     */
    public function edit(Category $category, Request $request): Response
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ($status_login == true) {
            
            $form = $this->createForm(CategoryType::class, $category);
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid())
            {
                $em = $this->managerRegistry->getManager();
                $em->flush();
                return $this->redirectToRoute('category');
            }
            return $this->render('admin/category/edit.html.twig', [
                "form" => $form->createView()
            ]);
        }else {
            return $this->redirectToRoute('home');
        }
    }
}