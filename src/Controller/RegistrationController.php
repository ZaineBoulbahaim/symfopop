<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;

class RegistrationController extends AbstractController
{
    // Ruta de registre
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        Security $security
    ): Response {
        // Si ja està autenticat, redirigim
        if ($this->getUser()) {
            return $this->redirectToRoute('app_product_index');
        }

        $user = new User();
        // Reutilitzem RegistrationFormType també per editar perfil
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashem la contrasenya abans de guardar
            $user->setPassword($passwordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Compte creat correctament! Benvingut/da a SymfoPop!');

            // Login automàtic després del registre
            return $security->login($user, 'App\Security\LoginFormAuthenticator', 'main');
        }

        return $this->render('registration/register.html.twig', ['form' => $form]);
    }
}