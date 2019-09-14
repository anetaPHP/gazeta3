<?php
/**
 * RegistrationController.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserTypeType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    /**
     * Registration Action for User.
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ManagerRegistry              $managerRegistry
     *
     * @return Response
     *
     * @Route("/rejestracja", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        ManagerRegistry $managerRegistry
    ): Response {
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
