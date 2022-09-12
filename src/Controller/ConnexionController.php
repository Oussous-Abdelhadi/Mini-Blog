<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Connexion extends AbstractController
{
    public function connexion()
    {
        return $this->render('connexion/connexion.html.twig');
    }
}