<?php

namespace App\Controller;

use App\Service\SendMailService;
use App\Form\ResetPasswordFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPasswordRequestFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UsersRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager,
        SendMailService $mail
    ): Response
    {

        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On va chercher l'utilisateur par son email

            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            if ($user) {
                // On génère un token de réinitialisation de mot de passe

                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // On génère l'URL de réinitialisation de mot de passe

                $url = $this->generateUrl('reset_pass', [
                    'token' => $token
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                // On créé les données du mail
                $context = [
                    'url' => $url,
                    'user' => $user
                ];

                // On envoie le mail

                $mail->sendMail(
                    'no-reply@monsite.net',
                    $user->getEmail(),
                    'Réinitialisation de votre mot de passe',
                    'password_reset',
                    $context
                );

                // On affiche un message de confirmation

                $this->addFlash('success', 'Un mail de réinitialisation de mot de passe vous a été envoyé !');
                return $this->redirectToRoute('app_login');

            }
            // Si l'utilisateur n'existe pas, on affiche un message d'erreur
            $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route(path: '/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UsersRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
        ): Response
    {
        // On vériefie si on a ce token dans la base de données

        $user = $usersRepository->findOneByResetToken($token);

        if ($user) {
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // On réinitialise le token

                $user->setResetToken(null);

                // On modifie le mot de passe

                $user->setPassword($passwordHasher->hashPassword($user, $form->get('password')->getData()));

                $entityManager->persist($user);
                $entityManager->flush();

                // On affiche un message de confirmation

                $this->addFlash('success', 'Votre mot de passe a bien été modifié !');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'PassForm' => $form->createView()
            ]);
        }
        $this->addFlash('danger', 'Ce jeton est invalide !');
        return $this->redirectToRoute('app_login');
    }

}
