<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AboutController extends AbstractController
{
    /**
     * @Route("/apropos", name="about")
     */
    public function about(): Response
    {
        $status_login = $this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED');
        return $this->render('about/about.html.twig',[
            'status_login' => $status_login
        ]);
    }
}