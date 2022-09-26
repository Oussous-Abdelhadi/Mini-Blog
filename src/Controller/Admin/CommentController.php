<?php 

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;



class CommentController extends AbstractController
{

    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }


    /**
     * @Route("admin/comment", name="comment")
     */

     public function show(CommentRepository $commentRepository, ArticleRepository $articleRepository)
     {
        $comments = $commentRepository->findAll();
        return $this->render('admin/comment/show.html.twig',[
            'commentaires' => $comments,
            'articles' => $articleRepository->findAll(),
        ]);
     }

    /**
     * 
     * @Route("admin/comment/{id}/delete", name="comment_delete")
    */

    public function delete(Comment $comment): RedirectResponse
    {
        $em = $this->managerRegistry->getManager();
        $em->remove($comment);
        $em->flush();
        return $this->redirectToRoute('comment');
    }
}