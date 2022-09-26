<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class RedirectController extends AbstractController
{
    /**
     * @Route("redirect", name="linkedin")
     */

    public function linkedin()
    {
        return $this->redirect('https://www.linkedin.com/in/abdelhadi-oussous-048493182/');
    }

    /**
     * @Route("youtube", name="youtube")
     */

    public function youTube()
    {
        return $this->redirect('https://www.youtube.com/channel/UCuGOKp0vFAaNJce-PT97D6g');
    }
}
