<?php 

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("admin/home", name="admin_home")
     */
    public function home()
    {
        return $this->render('admin/home.html.twig');
    }
}

