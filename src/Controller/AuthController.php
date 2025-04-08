<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('auth/home.html.twig'); // Create this file
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, DocumentManager $dm, UserPasswordHasherInterface $hasher): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $role = $request->request->get('role');

            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $user->setPassword($hasher->hashPassword($user, $password));
            $user->setRole($role); // ✅ Fix here

            $dm->persist($user);
            $dm->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/register.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
?>
