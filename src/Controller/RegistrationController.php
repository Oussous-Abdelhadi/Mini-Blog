<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{

    private $passwordEncoder;
    private $mailer;
    private $userRepository;
    private $managerRegistry;
    

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface,
     Mailer $mailer,
     UserRepository $userRepository,
     ManagerRegistry $managerRegistry)
    {
        $this->passwordEncoder = $userPasswordHasherInterface;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("inscription", name="register")
     */
    public function register(Request $request): Response
    {

         
        $user = new User;
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, $form->get("password")->getData())
            );
            $user->setToken($this->generateToken());
            $em = $this->managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success",
            "Inscription réussi ! Vous allez recevoir un email de confirmation, vérifie également tes spam.");
            $this->mailer->sendEmail($user->getEmail(), $user->getToken());
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @Route("/confirmer-mon-compte/{token}", name="confirm_account")
     */
    public function confirmAccount($token)
    {
        $user = $this->userRepository->findOneBy(['token' => $token]);

        if ($user) {
            $user->setToken(null);
            $user->setEnabled(true);
            $em = $this->managerRegistry->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success",
            "Votre compte est maintenant activé !");
            return $this->redirectToRoute('home');
        }else{
            $this->addFlash("error",
            "Ce compte n'esxiste pas !");
            return $this->redirectToRoute('home');
        }
        return $this->json($token);
    }
}