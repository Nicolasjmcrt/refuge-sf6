<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // on génère le JWT de l'utilisateur
            // On créé le header
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT',
            ];

            // On créé le payload
            $payload = [
                'user_id' => $user->getId(),
            ];

            // On génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));




            // On envoie un mail de confirmation

            $mail->sendMail(
                'no-reply@monsite.net',
                $user->getEmail(),
                'Confirmation de votre inscription sur le site Refuge Shelter',
                'register',
                [
                    'user' => $user,
                    'token' => $token,
                ]
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        // On vérifie si le token est valide, n'a pas expiré et qu'il correspond à un utilisateur

        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret')))
        {
            // On reécupère le payload du token
            $payload = $jwt->getPayload($token);

            // On récupère l'utilisateur

            $user = $usersRepository->find($payload['user_id']);

            // On vérifie que l'utilisateur existe et qu'il n'est pas déjà activé

            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);

                $this->addFlash('success', 'Votre compte a bien été activé !');

                return $this->redirectToRoute('profile_index');
            }
        }

        // Ici un problème se pose avec le token

        $this->addFlash('danger', 'Le token est invalide ou a expiré !');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoi-verif', name: 'resend_verif')]
    public function resendVerif(UsersRepository $usersRepository, SendMailService $mail, JWTService $jwt): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page !');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Votre compte est déjà activé !');
            return $this->redirectToRoute('profile_index');
        }

        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
        ];

        $payload = [
            'user_id' => $user->getId(),
        ];

        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        $mail->sendMail(
            'no-reply@animals-shelter.com',
            $user->getEmail(),
            'Confirmation de votre inscription sur le site Animals Shelter',
            'register',
            [
                'user' => $user,
                'token' => $token,
            ]
        );
        $this->addFlash('success', 'Email de vérification envoyé');
            return $this->redirectToRoute('profile_index');

    }

}
