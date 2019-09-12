<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserTypeType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormTypeInterface;
/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{

    /**
     * register action.
     *
     * @Route("/rejestracja", name="app_register")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ManagerRegistry              $managerRegistry
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(UserTypeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('password')->getData()));

            $user->setLoginname($form->get('loginname')->getData());

            $user->setRoles($user->getRoles());

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('app_stronastartowa');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);


    }


}
